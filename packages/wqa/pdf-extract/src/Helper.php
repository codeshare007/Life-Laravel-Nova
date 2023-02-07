<?php

namespace Wqa\PdfExtract;

class Helper
{
    public static function mergeLines($html, $condition)
    {
        // Todo: rewrite as laravel Collection
        foreach ($html as $key => &$line) {

            $nextLine = $html[$key+1] ?? false;
            if (!$nextLine) {
                continue;
            }

            $currentValue = static::getPositionValue($condition['position'], $line);
            $nextValue = static::getPositionValue($condition['position'], $nextLine);

            $currentClassAttr = static::getClassValue($line);
            $nextClassAttr = static::getClassValue($nextLine);

            if (!$currentValue || !$nextValue) {
                continue;
            }

            $diff = abs($nextValue-$currentValue);
            $currentText = trim(preg_replace('/\s+/u', ' ', strip_tags($line)));
            $nextText = trim(preg_replace('/\s+/u', ' ', trim(strip_tags($nextLine))));

            if ($diff < $condition['diff'] && $currentClassAttr == $nextClassAttr) { // Haunt us later ???
                if ($nextText && !strstr($nextText,"- ")) {
                    $line = str_replace($currentText, $currentText . '' . $nextText, $line);
                    unset($html[$key+1]);
                }
            }

            // Conditions only
            if (strtoupper($currentText) == $currentText) {
                unset($html[$key]);
            }
        }

        return $html;
    }

    public static function mergeSameClass($currentKey, $lines)
    {
        $merged = [];
        $groupKey = 0;

        $merged = [$currentKey => []];
        $lines = array_slice($lines, $currentKey, count($lines), true);
        foreach ($lines as $key => $line) {

            $nextLine = $lines[$key+1] ?? false;
            if (!$nextLine) {
                continue;
            }

            $currentClassAttr = static::getClassValue($line);
            $nextClassAttr = static::getClassValue($nextLine);

            $currentText = trim(preg_replace('/\s+/u', ' ', strip_tags($line)));
            $nextText = trim(preg_replace('/\s+/u', ' ', trim(strip_tags($nextLine))));

            $currentValue = static::getPositionValue('top', $line);
            $nextValue = static::getPositionValue('top', $nextLine);

            $merged[$currentKey][$key] = $currentText;
            $diff = abs($nextValue-$currentValue);
            if ($currentClassAttr !== $nextClassAttr) {
                break;
            }
        }

        $deleteKeys = array_keys($merged[$currentKey]);
        array_shift($deleteKeys);

        if (isset($merged[$currentKey])) {
            return [
                'mergedText' => implode(' ', $merged[$currentKey]),
                'deleteKeys' => $deleteKeys
            ];
        }

        return [];
    }

    /**
     * @param $data
     * @return array
     */
    public static function extractGroupData($html, $config)
    {
        $groupData = [];
        $html = static::positionTopLeftModifier($html);
        $lines = Collect($html)->flatten()->toArray();

        $deleteKeys = [];
        //dump($lines);

        foreach ($lines as $key => $line) {

            $currentText = strip_tags($line);
            $merged = static::mergeSameClass($key, $lines);
            $line = str_replace($currentText, $merged['mergedText'], $line);

            $lines[$key] = $line;
            if (isset($merged['deleteKeys'])) {
                $deleteKeys = array_merge($deleteKeys, $merged['deleteKeys']);
            }
        }

        foreach (array_unique($deleteKeys) as $deleteKey) {
            unset($lines[$deleteKey]);
        }

        $layout = new Layout(array_values($lines), $config);
        $html = static::positionTopLeftModifier($layout->getHtml());

        foreach ($html as $column => $lines) {
            $groupData[$column] = static::mergeSameClassGroupRecursive($lines, 0);
        }

        return $groupData;
    }

    /**
     * @param $lines
     * @param $currentKey
     * @param int $groupKey
     * @param int $key
     * @param array $mergedLines
     * @return array
     */
    public static function mergeSameClassGroupRecursive($lines, $currentKey, $groupKey = 1, $key = 0, $mergedLines = [])
    {
        $mergedLines['group_'.$groupKey] = $mergedLines['group_'.$groupKey] ?? [];

        $currentText = trim(preg_replace('/\s+/u', ' ', trim(strip_tags($lines[$key]))));

        $currentText = preg_replace('/\s+/u', ' ', $currentText);
        if (stristr($currentText," see")) {
            $currentText = stristr($currentText," see", true);
            $currentText = trim($currentText, ';');
        }

        if(!empty($currentText)) {
            array_push($mergedLines['group_' . $groupKey], $currentText);
        }

        if (!isset($lines[$key+1])) {
            return $mergedLines;
        }

        $currentClassAttr = static::getClassValue($lines[$key]);
        $nextClassAttr = static::getClassValue($lines[$key+1]);

        if ($currentClassAttr !== $nextClassAttr) {
            if (count($mergedLines['group_'.$groupKey]) > 1) {
                $groupKey += 1;
            }
        }

        return static::mergeSameClassGroupRecursive($lines, $currentKey, $groupKey, $key+1, $mergedLines);
    }

    public static function positionTopLeftModifier($data)
    {
        $positions = [];
        foreach ($data as $column => $lines) {
            $positions[$column] = [];
            foreach ($lines as $key => $line) {
                $topValue = Helper::getPositionValue('top', $line);
                $leftValue = Helper::getPositionValue('left', $line);
                if (!$topValue || !$leftValue) {
                    continue;
                }

                $positions[$column][$key] = $topValue.'.'.($leftValue+1000);
                // 441.140
                // 441.93

            }
            asort($positions[$column]);
        }

        $dataToSort = [];
        foreach ($positions as $column => $positionKeys) {
            foreach ($positionKeys as $k => $positionKey) {
                $dataToSort[$column][] = $data[$column][$k];
            }
        }

        return $dataToSort;
    }

    public static function getPositionValue($positiion, $line)
    {
        preg_match("/$positiion:(.*?)px;/i", $line, $matches);
        return $matches[1] ?? false;
    }

    public static function getClassValue($line)
    {
        preg_match("/class=\"(.*?)\"/i", $line, $matches);
        return $matches[1] ?? false;
    }
}
