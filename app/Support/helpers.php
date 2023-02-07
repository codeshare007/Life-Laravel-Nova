<?php

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as CollectionModel;
use Illuminate\Http\Request;

if (! function_exists('isUuid')) {
    /**
     * @param $uuid
     * @return bool
     */
    function isUuid($uuid)
    {
        return is_string($uuid) && (bool) preg_match('/^[a-f0-9]{8,8}-(?:[a-f0-9]{4,4}-){3,3}[a-f0-9]{12,12}$/i', $uuid);
    }
}

if (! function_exists('resourceToClass')) {
    /**
     * Given a resource type, load the class associated with it
     * @param $resource
     * @return string
     * @throws Exception
     */
    function resourceToClass($resource)
    {
        if ($resource == \App\Enums\AilmentType::getKey(\App\Enums\AilmentType::Symptom)) {
            return \App\Ailment::class;
        }

        if (in_array($resource, [
            \App\Enums\TagType::getKey(\App\Enums\TagType::Property),
            \App\Enums\TagType::getKey(\App\Enums\TagType::Constituent)
        ])) {
            return \App\Tag::class;
        }

        if (! class_exists('App\\' . $resource)) {
            throw new \Exception('Resource type doesn\'t exist');
        }

        return 'App\\' . $resource;
    }
}

if (! function_exists('currentUserRequest')) {
    /**
     * Check for query to elements endpoint
     * @param Request $request
     * @return bool
     */
    function currentUserRequest(Request $request)
    {
        return strpos($request->getUri(), '/currentUser') !== false;
    }
}

if (! function_exists('elementsRequest')) {
    /**
     * Check for query to elements endpoint
     * @param Request $request
     * @return bool
     */
    function elementsRequest(Request $request)
    {
        return strpos($request->getUri(), '/elements/') !== false;
    }
}

if (! function_exists('showFullDetails')) {
    /**
     * Check for query to elements endpoint
     * @param Request $request
     * @return bool
     */
    function showFullDetails(Request $request)
    {
        return elementsRequest($request) || $request->get('offline', false);
    }
}

if (! function_exists('isFirstPage')) {
    /**
     * Check if the request has pagination and
     * is requesting the first page
     * @param Request $request
     * @return bool
     */
    function isFirstPage(Request $request)
    {
        return !$request->has('page') || $request->get('page') == 1;
    }
}

if (! function_exists('purifyModelHtml')) {
    /**
     * @param CollectionModel $collection
     * @param $attributes
     * @param array $overrideRules
     * @return CollectionModel|Collection
     */
    function purifyModelHtml(CollectionModel $collection, $attributes, $overrideRules = [], $saveModel = true)
    {
        return $collection->map(function ($item) use ($attributes, $overrideRules, $saveModel) {
            foreach ($attributes as $attribute) {
                $item->$attribute = purifyHtml($item->$attribute, $overrideRules);
            }
            if ($saveModel) $item->save();
            return $item;
        });
    }
}

if (! function_exists('purifyHtml')) {
    /**
     * @param $html
     * @param $overrideRules
     * @param bool $removeUtf8Spaces
     * @return mixed
     */
    function purifyHtml($html, $overrideRules = [], $utf8Spaces = true)
    {
        $htmlAllowed = $overrideRules['html'] ?? '';
        $cssAllowed = $overrideRules['css'] ?? '';

        $rules = [
            'HTML.Allowed' => $htmlAllowed,
            'CSS.AllowedProperties' => $cssAllowed,
        ];

        $html = ($utf8Spaces ? preg_replace('/\s+/u', ' ', $html) : $html);
        return sentenceCase(clean($html, $rules));
    }
}

if (! function_exists('sentenceCase')) {
    function sentenceCase($string) {
        $sentences = preg_split('/([.?!]+)/', $string, -1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $newString = '';
        foreach ($sentences as $key => $sentence) {
            $newString .= ($key & 1) == 0 ? ucfirst(strtolower(trim($sentence))) : $sentence.' ';
        }
        return trim($newString);
    }
}

if (! function_exists('getFieldMaxLength')) {
    /**
     * @param CollectionModel $collection
     * @param $attribute
     * @return int|null|string
     */
    function getFieldMaxLength(CollectionModel $collection, $attribute)
    {
        $fieldLength = [];
        foreach ($collection as $item) {
            $length = strlen($item->$attribute);
            $fieldLength[$length] = $item;
        }

        ksort($fieldLength);
        end($fieldLength);
        return key($fieldLength);
    }
}

if (! function_exists('multiSplitString')) {
    /**
     * @param $delimiters
     * @param $string
     * @return array
     */
    function multiSplitString($delimiters, $string)
    {
        return array_values(array_filter(array_map('trimStr', preg_split('/['.$delimiters.']/', $string))));
    }
}

if (! function_exists('trimStr')) {
    /**
     * @param $string
     * @return null|string|string[]
     */
    function trimStr($string)
    {
        return preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $string);
    }
}

if (! function_exists('putImagesOnCloud')) {
    /**
     * @param Collection $collection
     * @param $attribute
     * @return Collection
     */
    function putImagesOnCloud(Collection $collection, $attribute)
    {
        return $collection->map(function ($item) use ($attribute) {
            $item->$attribute = '[IMAGE S3] '.$item->$attribute;
            return $item;
        });
    }
}
