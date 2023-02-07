<?php

namespace App\Console\Commands;

use App\Ailment;
use App\Blend;
use App\Oil;
use App\Usage;
use Illuminate\Console\Command;
use Gufy\PdfToHtml;

class PdfExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:extract {page?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract information from PDF';

    protected $pdf;

    private function setPdfConfig()
    {
        PdfToHtml\Config::set('pdftohtml.bin', '/usr/local/bin/pdftohtml');
        PdfToHtml\Config::set('pdfinfo.bin', '/usr/local/bin/pdfinfo');
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setPdfConfig();


        //$filename = '5th_Edition_Essential_Life-oils.pdf';
        $filename = '3 Natural Solutions Singles Even Smaller.pdf';

        $this->pdf = new PdfToHtml\Pdf(storage_path($filename));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->pdf->pages = [];
        $this->getPages();

        dd($this->pdf->pages);
    }

    protected function updateUsages($ailments, $content, $pageNumber) {

//        $ailmentsArr = [];
//        $ailmentsExp = explode(' &amp; ', $ailments);
//        foreach ($ailmentsExp as $ailment) {
//            if (strpos($ailment, ',') !== false) {
//                $ailmentsArr = array_merge(explode(',', trim($ailment)), $ailmentsArr);
//            } else {
//                $ailmentsArr[] = trim($ailment);
//            }
//        }
//
//        $ailmentsAssoc = [];
//        $ailmentsNotExist = [];
//        foreach ($ailmentsArr as $ailmentName) {
//            $ailment = Ailment::where('name', 'LIKE', '%'.trim($ailmentName).'%');
//            if ($ailment->exists()) {
//                $ailmentsAssoc[] = $ailment->value('id');
//            }
//        }

        $ailmentsAssoc = [];

        $oil = Oil::where('page_number', 156);
        if (!$oil->exists()) {
            $this->error(PHP_EOL.'Sorry could not find this page:'.$pageNumber);
            return;
        }

        $usage = new Usage;
        $usage->fill([
            'useable_type' => 'App\Oil',
            'useable_id' => $oil->value('id'),
            'name' => html_entity_decode($ailments),
            'description' => $content,
            'ailments' => $ailmentsAssoc
        ]);
        if ($usage->save()) {
            $usage->fresh();
            $usage->ailments()->sync($ailmentsAssoc);
            $this->info($oil->value('name').' usages successfully populated page:'.$pageNumber);
        }
    }

    private function getPages()
    {
        if ($page = $this->argument('page')) {
            $this->process($page);
        } else {
            $progress = $this->output->createProgressBar($this->pdf->getPages());
            $progress->start();
            for ($i = 1; $i <= $this->pdf->getPages(); $i++) {
                $this->process($i);
                $progress->advance();
                $this->info(PHP_EOL);
            }
            $progress->finish();
        }
    }

    private function process($page)
    {
        $data = $this->parseHtml($this->html($page), $pageNumber);
        $this->pdf->pages[$page] = $data;

        //dd($data, $pageNumber)
        foreach ($data as $ailments => $content) {
            $this->updateUsages($ailments, $content, $pageNumber);
        }
    }

    /**
     * @param $page
     * @param $pageNumber
     * @return array
     */
    protected function parseHtml($page, &$pageNumber)
    {
        $lines = Collect(explode(PHP_EOL, $page));

        // Search TOP USES => Remove previous lines
        $index = $lines->search(function($item){
            return strstr($item, 'TOP USES');
        });
        $lines = $lines->slice($index+1)->values();

        $pageNumber = $this->extractPageNumber($lines);
        //dd($lines, $pageNumber);

        preg_match_all('#left:(.*?)px#i', $lines[0], $line);
        $leftPosition = $line[1][0] ?? 0;
        $pagePosition = $leftPosition < 300 ? 'left' : 'right';

        $index = $lines->search(function($item) use ($pagePosition, $leftPosition) {
            preg_match_all('#left:(.*?)px#i', $item, $line);
            $leftPos = $line[1][0] ?? 0;
            $leftPos = (int)$leftPos;
            $leftPosition = (int)$leftPosition;
            //dd($leftPos, $leftPosition, $pagePosition);
            if ($pagePosition == 'left') {
                return $leftPos > $leftPosition+300;
            } else {
                return $leftPosition > $leftPos+10;
            }
        });

        //$lines->splice($index);

        $lines = $lines->map(function ($line) {
            $line = str_replace('\n', '', strip_tags($line));
            $line = str_replace(' A ', ' [A] ', $line);
            $line = str_replace(' I ', ' [I] ', $line);
            $line = str_replace(' T ', ' [T] ', $line);
            return trim($line);
        })->all();

        $groupKeys = [];
        foreach ($lines as $key => $line) {
            if ((bool)preg_match('/[A-Z]{3,}/', $line)) {
                $groupKeys[] = $key;
            }
        }

        if (!$groupKeys) {
            return [];
        }

        $groups = [];
        $index = 0;
        foreach ($lines as $key => $line) {

            if (empty(trim($line))) {
                continue;
            }

            $current = $groupKeys[$index];
            $next = $groupKeys[$index+1] ?? false;


            $lastKey = end($groupKeys);
            if ($key >= $lastKey) {
                $groups[$lastKey][] = $line;
            }

            if ($next == false) {
                if (isset($groups[$lastKey]) && is_array($groups[$lastKey])) {
                    array_push($groups[$lastKey], end($lines));
                }
                break;
            }

            //dd($key .' >= '. $current .':'. $key .' < '. $next);
            //if ($key >= $current && $key < $next) {
            if ($key < $next) {
                $groups[$current][] = $line;
                array_unshift($groups[$current], $lines[$current]);
                $groups[$current] = array_unique($groups[$current]);
            } else {
                $index++;
            }
        }

        $groupsArr = [];
        foreach ($groups as $group) {
            $groupKey = $group[0];
            unset($group[0]);
            $groupsArr[$groupKey] = implode('<br>', $group);
        }

        return $groupsArr;
    }

    protected function extractPageNumber($lines) {

        $lines = $lines->map(function ($line) {
            return strip_tags($line);
        })->filter(function ($value, $key) {
            return strlen($value) <= 3 && is_numeric($value);
        })->values();

        return (int)$lines[0] ?? 0;
    }

    protected function html($page) {

        return $this->pdf->html($page);

        return '<body bgcolor="#A0A0A0" vlink="blue" link="blue">\n
            <div id="page1-div" style="position:relative;width:918px;height:1174px;">\n
            <img width="918" height="1174" src="5th_Edition_Essential_Life-oils001.png" alt="background image">\n
            <p style="position:absolute;top:1001px;left:422px;white-space:nowrap" class="ft00"><i>Arborvitae trees can live for over  </i></p>\n
            <p style="position:absolute;top:1019px;left:419px;white-space:nowrap" class="ft00"><i>800 years. It’s no surprise that </i></p>\n
            <p style="position:absolute;top:1034px;left:416px;white-space:nowrap" class="ft00"><i>Arborvitae means ”Tree of Life.”</i></p>\n
            <p style="position:absolute;top:1031px;left:587px;white-space:nowrap" class="ft01"><i> </i></p>\n
            <p style="position:absolute;top:250px;left:752px;white-space:nowrap" class="ft02"><b>MAIN</b></p>\n
            <p style="position:absolute;top:266px;left:722px;white-space:nowrap" class="ft02"><b>CONSTITUENTS</b></p>\n
            <p style="position:absolute;top:283px;left:736px;white-space:nowrap" class="ft03">Methyl thujate</p>\n
            <p style="position:absolute;top:299px;left:754px;white-space:nowrap" class="ft03">Hinokitiol</p>\n
            <p style="position:absolute;top:316px;left:756px;white-space:nowrap" class="ft03">Thujic acid</p>\n
            <p style="position:absolute;top:644px;left:780px;white-space:nowrap" class="ft02"><b>FOUND IN</b></p>\n
            <p style="position:absolute;top:661px;left:768px;white-space:nowrap" class="ft03">Repellent blend</p>\n
            <p style="position:absolute;top:686px;left:783px;white-space:nowrap" class="ft02"><b>BLENDS</b></p>\n
            <p style="position:absolute;top:703px;left:770px;white-space:nowrap" class="ft02"><b>WELL WITH</b></p>\n
            <p style="position:absolute;top:719px;left:790px;white-space:nowrap" class="ft03">Birch</p>\n
            <p style="position:absolute;top:736px;left:769px;white-space:nowrap" class="ft03">Cedarwood</p>\n
            <p style="position:absolute;top:752px;left:762px;white-space:nowrap" class="ft03">Frankincense</p>\n
            <p style="position:absolute;top:769px;left:767px;white-space:nowrap" class="ft03">Siberian fir</p>\n
            <p style="position:absolute;top:874px;left:753px;white-space:nowrap" class="ft02"><b>SAFETY</b></p>\n
            <p style="position:absolute;top:891px;left:750px;white-space:nowrap" class="ft03">Dilution</p>\n
            <p style="position:absolute;top:908px;left:724px;white-space:nowrap" class="ft03">recommended.</p>\n
            <p style="position:absolute;top:924px;left:727px;white-space:nowrap" class="ft03">Possible skin </p>\n
            <p style="position:absolute;top:940px;left:727px;white-space:nowrap" class="ft03">sensitivity. </p>\n
            <p style="position:absolute;top:437px;left:792px;white-space:nowrap" class="ft02"><b>TOP</b></p>\n
            <p style="position:absolute;top:453px;left:765px;white-space:nowrap" class="ft02"><b>PROPERTIES</b></p>\n
            <p style="position:absolute;top:470px;left:785px;white-space:nowrap" class="ft03">Antiviral </p>\n
            <p style="position:absolute;top:486px;left:780px;white-space:nowrap" class="ft03">Antifungal</p>\n
            <p style="position:absolute;top:503px;left:775px;white-space:nowrap" class="ft03">Antibacterial</p>\n
            <p style="position:absolute;top:519px;left:787px;white-space:nowrap" class="ft03">Stimulant</p>\n
            <p style="position:absolute;top:120px;left:712px;white-space:nowrap" class="ft02"><b>STEAM</b></p>\n
            <p style="position:absolute;top:137px;left:713px;white-space:nowrap" class="ft02"><b>DISTILLED</b></p>\n
            <p style="position:absolute;top:78px;left:709px;white-space:nowrap" class="ft02"><b>WOOD</b></p>\n
            <p style="position:absolute;top:95px;left:718px;white-space:nowrap" class="ft02"><b>PULP</b></p>\n
            <p style="position:absolute;top:988px;left:656px;white-space:nowrap" class="ft04">RESEARCH: </p>\n
            <p style="position:absolute;top:989px;left:712px;white-space:nowrap" class="ft05">Effect of arborvitae seed on </p>\n
            <p style="position:absolute;top:999px;left:651px;white-space:nowrap" class="ft05">cognitive function and a-7nAChR protein expression  </p>\n
            <p style="position:absolute;top:1010px;left:645px;white-space:nowrap" class="ft05">of hippocampus in model rats with Alzheimer’s disease, </p>\n
            <p style="position:absolute;top:1020px;left:639px;white-space:nowrap" class="ft05">Cheng XL, Xiong XB, Xiang MQ, </p>\n
            <p style="position:absolute;top:1019px;left:762px;white-space:nowrap" class="ft06"><i>Cell Biochem Biophys</i></p>\n
            <p style="position:absolute;top:1020px;left:854px;white-space:nowrap" class="ft07"><i>,</i></p>\n
            <p style="position:absolute;top:1020px;left:856px;white-space:nowrap" class="ft05"> </p>\n
            <p style="position:absolute;top:1031px;left:633px;white-space:nowrap" class="ft05">2013 </p>\n
            <p style="position:absolute;top:1045px;left:624px;white-space:nowrap" class="ft05">Conservative surgical management of stage IA endometrial </p>\n
            <p style="position:absolute;top:1055px;left:618px;white-space:nowrap" class="ft05">carcinoma for fertility preservation, Mazzon I, Corrado G, Masci-</p>\n
            <p style="position:absolute;top:1066px;left:612px;white-space:nowrap" class="ft05">ullo V, Morricone D, Ferrandina G, Scambia G., </p>\n
            <p style="position:absolute;top:1065px;left:791px;white-space:nowrap" class="ft06"><i>Fertil Steril</i></p>\n
            <p style="position:absolute;top:1066px;left:838px;white-space:nowrap" class="ft07"><i>, </i></p>\n
            <p style="position:absolute;top:1066px;left:843px;white-space:nowrap" class="ft05">2010</p>\n
            <p style="position:absolute;top:321px;left:78px;white-space:nowrap" class="ft08"><b>TOP USES</b></p>\n
            <p style="position:absolute;top:362px;left:79px;white-space:nowrap" class="ft02"><b>CANDIDA &amp; FUNGAL ISSUES</b></p>\n
            <p style="position:absolute;top:377px;left:78px;white-space:nowrap" class="ft09">Apply</p>\n
            <p style="position:absolute;top:379px;left:116px;white-space:nowrap" class="ft03"> T </p>\n
            <p style="position:absolute;top:377px;left:135px;white-space:nowrap" class="ft09">to bottoms of feet or area  </p>\n
            <p style="position:absolute;top:394px;left:78px;white-space:nowrap" class="ft09">of concern. </p>\n
            <p style="position:absolute;top:422px;left:79px;white-space:nowrap" class="ft02"><b>VIRUSES</b></p>\n
            <p style="position:absolute;top:439px;left:78px;white-space:nowrap" class="ft03">Apply T </p>\n
            <p style="position:absolute;top:437px;left:131px;white-space:nowrap" class="ft09">to bottoms of feet.</p>\n
            <p style="position:absolute;top:466px;left:79px;white-space:nowrap" class="ft02"><b>COLD SORES &amp; WARTS</b></p>\n
            <p style="position:absolute;top:482px;left:78px;white-space:nowrap" class="ft03">Apply T </p>\n
            <p style="position:absolute;top:481px;left:131px;white-space:nowrap" class="ft09">to sore or wart frequently.</p>\n
            <p style="position:absolute;top:509px;left:79px;white-space:nowrap" class="ft02"><b>RESPIRATORY ISSUES</b></p>\n
            <p style="position:absolute;top:526px;left:78px;white-space:nowrap" class="ft03">Apply T to chest with fractionated  </p>\n
            <p style="position:absolute;top:542px;left:78px;white-space:nowrap" class="ft03">coconut oil.</p>\n
            <p style="position:absolute;top:569px;left:79px;white-space:nowrap" class="ft02"><b>REPELLENT</b></p>\n
            <p style="position:absolute;top:586px;left:78px;white-space:nowrap" class="ft03">Diffuse A, spray on surfaces, or apply </p>\n
            <p style="position:absolute;top:603px;left:78px;white-space:nowrap" class="ft03">T</p>\n
            <p style="position:absolute;top:602px;left:91px;white-space:nowrap" class="ft03"> to repel bugs and insects.</p>\n
            <p style="position:absolute;top:629px;left:79px;white-space:nowrap" class="ft02"><b>CANCER</b></p>\n
            <p style="position:absolute;top:646px;left:78px;white-space:nowrap" class="ft03">Apply T to bottoms of feet.</p>\n
            <p style="position:absolute;top:673px;left:79px;white-space:nowrap" class="ft02"><b>STIMULANT</b></p>\n
            <p style="position:absolute;top:689px;left:78px;white-space:nowrap" class="ft03">Apply T under nose and back of neck  </p>\n
            <p style="position:absolute;top:706px;left:78px;white-space:nowrap" class="ft03">to stimulate body systems and awareness.</p>\n
            <p style="position:absolute;top:733px;left:79px;white-space:nowrap" class="ft02"><b>BACTERIAL SUPPORT</b></p>\n
            <p style="position:absolute;top:749px;left:78px;white-space:nowrap" class="ft03">Apply T to bottoms of feet or spine with  </p>\n
            <p style="position:absolute;top:766px;left:78px;white-space:nowrap" class="ft03">fractionated coconut oil. Spray diluted in  </p>\n
            <p style="position:absolute;top:782px;left:78px;white-space:nowrap" class="ft03">water on surfaces or diffuse A to kill  </p>\n
            <p style="position:absolute;top:799px;left:78px;white-space:nowrap" class="ft03">airborne pathogens.</p>\n
            <p style="position:absolute;top:826px;left:79px;white-space:nowrap" class="ft02"><b>SKIN COMPLAINTS</b></p>\n
            <p style="position:absolute;top:842px;left:78px;white-space:nowrap" class="ft03">Apply T with lavender to troubled skin.</p>\n
            <p style="position:absolute;top:869px;left:79px;white-space:nowrap" class="ft02"><b>SUNSCREEN</b></p>\n
            <p style="position:absolute;top:886px;left:78px;white-space:nowrap" class="ft03">Apply T with helichrysum or lavender  </p>\n
            <p style="position:absolute;top:902px;left:78px;white-space:nowrap" class="ft03">to protect against sun exposure.</p>\n
            <p style="position:absolute;top:929px;left:79px;white-space:nowrap" class="ft02"><b>MEDITATION</b></p>\n
            <p style="position:absolute;top:946px;left:78px;white-space:nowrap" class="ft03">Diffuse A to enhance spiritual</p>\n
            <p style="position:absolute;top:962px;left:78px;white-space:nowrap" class="ft03">awareness and state of calm.</p>\n
            <p style="position:absolute;top:989px;left:79px;white-space:nowrap" class="ft02"><b>EMOTIONAL BALANCE</b></p>\n
            <p style="position:absolute;top:1006px;left:78px;white-space:nowrap" class="ft03">Use A aromatically and T topically to get </p>\n
            <p style="position:absolute;top:1022px;left:78px;white-space:nowrap" class="ft03">from Overzealous  ---------------------------&gt;  Compose</p>\n
            <p style="position:absolute;top:1022px;left:303px;white-space:nowrap" class="ft010">d.</p>\n
            <p style="position:absolute;top:122px;left:411px;white-space:nowrap" class="ft011">woody </p>\n
            <p style="position:absolute;top:161px;left:411px;white-space:nowrap" class="ft011">  majestic</p>\n
            <p style="position:absolute;top:200px;left:411px;white-space:nowrap" class="ft011">     strong</p>\n
            <p style="position:absolute;top:1120px;left:82px;white-space:nowrap" class="ft09">A </p>\n
            <p style="position:absolute;top:1123px;left:100px;white-space:nowrap" class="ft012">= Aromatically  </p>\n
            <p style="position:absolute;top:1120px;left:181px;white-space:nowrap" class="ft09">T</p>\n
            <p style="position:absolute;top:1120px;left:195px;white-space:nowrap" class="ft09"> </p>\n
            <p style="position:absolute;top:1123px;left:199px;white-space:nowrap" class="ft012">= Topically  </p>\n
            <p style="position:absolute;top:1120px;left:259px;white-space:nowrap" class="ft09">I</p>\n
            <p style="position:absolute;top:1120px;left:273px;white-space:nowrap" class="ft09"> </p>\n
            <p style="position:absolute;top:1123px;left:276px;white-space:nowrap" class="ft012">=  Internally</p>\n
            <p style="position:absolute;top:122px;left:85px;white-space:nowrap" class="ft013"><b>ARBORVITAE</b></p>\n
            <p style="position:absolute;top:189px;left:121px;white-space:nowrap" class="ft014"><i>T H U J A   P L I C A TA</i></p>\n
            <p style="position:absolute;top:515px;left:894px;white-space:nowrap" class="ft015">SINGLE</p>\n
            <p style="position:absolute;top:476px;left:894px;white-space:nowrap" class="ft015"> OILS</p>\n
            <p style="position:absolute;top:1102px;left:748px;white-space:nowrap" class="ft016">life</p>\n
            <p style="position:absolute;top:1126px;left:652px;white-space:nowrap" class="ft05">THE</p>\n
            <p style="position:absolute;top:1123px;left:673px;white-space:nowrap" class="ft017">ESSENTIAL</p>\n
            <p style="position:absolute;top:1123px;left:841px;white-space:nowrap" class="ft018">79</p>\n
            </div>\n
            </body>';
    }
}
