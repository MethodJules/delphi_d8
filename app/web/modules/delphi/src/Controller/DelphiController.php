<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 18.05.19
 * Time: 20:17
 */

namespace Drupal\delphi\Controller;


use Drupal\Core\Controller\ControllerBase;

class DelphiController extends ControllerBase
{
    public function content() {
        return ['#markup' => 'delphi'];
    }

    public function page() {
        /**
         * Only for test puposes delete afterwards
         */
        $answersOverall = [0,0,0,1,2,2,2,2,3,3,3,4,4,4,4,4,4];
        $avg = $this->survey_get_average($answersOverall, true, 4);
        $median = $this->survey_get_median($answersOverall, true, 4);
        $first_quantil = $this->survey_get_first_quantil($answersOverall, true, 4);
        $third_quantil = $this->survey_get_third_quantil($answersOverall, true, 4);
        $third_quantil2 = $third_quantil["procent"] - $first_quantil["procent"];


        //dsm($avg);


        return array(
            '#theme' => 'delphi_boxplot',
            '#avg_percent' => $avg['procent'],
            '#avg_absolut' => $avg['absolut'],
            '#median_percent' => $median['procent'],
            '#median_absolut' => $median['absolut'],
            '#first_quantil_percent' => $first_quantil['procent'],
            '#first_quantil_absolut' => $first_quantil['absolut'],
            '#third_quantil_percent' => $third_quantil['procent'],
            '#third_quantil_absolut' => $third_quantil['absolut'],
            '#third_quantil2' => $third_quantil2,
        );
    }


    //this function should be deprecated
    public function createBoxplot($testArrayOverall = [0,0,0,1,2,2,2,2,3,3,3,4,4,4], $isRadioButton  = true) {

        //Die einzelnen Bestandteile eines Boxplots werden ermittelt
        $min = 0;
        // Maximum der Boxplot-Skala, k.A. nicht Teil der Skala
        $indexNoAnswer = 5; //TODO Parameter
        $max = $indexNoAnswer - 1;
        // TODO Berechnungfunktionen prüfen und refaktorieren, unnötige Unterscheidung, die nur wieder +1 addiert
        $avg = $this->survey_get_average($testArrayOverall, $isRadioButton, $max);
        //dsm($avg['procent']);

        $median = $this->survey_get_median($testArrayOverall, $isRadioButton, $max);

        //dsm($median['procent']);
        $first_quantil = $this->survey_get_first_quantil($testArrayOverall, $isRadioButton, $max);

        $third_quantil = $this->survey_get_third_quantil($testArrayOverall, $isRadioButton, $max);
        $third_quantil2 = $third_quantil["procent"] - $first_quantil["procent"];

        $links = array(0 => "unteres Quartil", 1 => "Mittelwert", 2 => "Median", 3 => "oberes Quartil");




        //create the boxplot
        $boxplot_string = array(
            'container' => array(
                '#prefix' => '<div id="eins">',
                '#suffix' => '</div>',
                'boxplot' => array(
                    '#prefix' => '<div class="boxplot">',
                    '#suffix' => '</div>',
                    'boxlinie' => array(
                        '#prefix' => '<div class="box linie">',
                        '#suffix' => '</div>',
                    ),
                    'boxwhisker' => array(
                        '#prefix' => '<div class="box whisker">',
                        '#suffix' => '</div>',
                    ),
                    'boxinterquant' => array(
                        '#prefix' => '<div class="box interquart" style="left: ' . $first_quantil["procent"] . '%;">',
                        '#suffix' => '</div>',
                    ),
                    'boxthirdquant' => array(
                        '#prefix' => '<div class="box thirdquant" style="left: calc(' . $third_quantil2 . '% + ' . $first_quantil["procent"] . '%); width: ' . $third_quantil2 . '%; margin-left: -' . $third_quantil2 . '%;">',
                        '#suffix' => '</div>',
                    ),
                    'boxmedian' => array(
                        '#prefix' => '<div class="box median" style="left: ' . $median["procent"] . '%;">',
                        '#suffix' => '</div>',
                    ),
                    'boxmittel' => array(
                        '#prefix' => '<div class="box mittel" style="left: ' . $avg["procent"] . '%;">',
                        '#suffix' => '</div>',
                    ),





                    's_min' => array(
                        '#prefix' => '<span class="schild s_min" style="left: 0%;">',
                        '#suffix' => '</span>',
                        'markup' => array(
                            '#markup' => $this->_formatNumber($min),
                        ),
                    ),
                    's_average' => array(
                        '#prefix' => '<span class="schild min s_average" style="margin-left: ' . $avg["procent"] . '%;">',
                        '#suffix' => '</span>',
                        'markup' => array(
                            '#markup' => $this->_formatNumber($avg["absolut"]),
                        ),
                    ),
                    's_median' => array(
                        '#prefix' => '<span class="schild min s_median" style="margin-left: ' . $median["procent"] . '%;"> ',
                        '#suffix' => '</span>',
                        'markup' => array(
                            '#markup' => $this->_formatNumber($median["absolut"]),
                        ),
                    ),
                    's_third_quantil' => array(
                        '#prefix' => '<span class="schild min s_third_quantil" style="margin-left: ' . $third_quantil["procent"] . '%;"> ',
                        '#suffix' => '</span>',
                        'markup' => array(
                            '#markup' =>  $this->_formatNumber($third_quantil["absolut"]),
                        ),
                    ),
                    's_first_quantil' => array(
                        '#prefix' => '<span class="schild s_first_quantil" style="margin-left: ' . $first_quantil["procent"] . '%;"> ',
                        '#suffix' => '</span>',
                        'markup' => array(
                            '#markup' => $this->_formatNumber($first_quantil["absolut"]),
                        ),
                    ),
                    's_max' => array(
                        '#prefix' => '<span class="schild min s_max" style="margin-left: 100%;"> ',
                        '#suffix' => '</span>',
                        'markup' => array(
                            '#markup' => $this->_formatNumber($max),
                        ),
                    ),
                ),
                'legend' => array(
                    '#theme' => 'item_list',
                    '#items' => $links,
                    '#type' => 'ul',
                    '#prefix' => '<div class="legend">',
                    '#suffix' => '</div>',
                    '#attributes' => array('class' => 'my-list'),
                ),
            ),
        );

        $page = [
            'boxplot' => $boxplot_string,
        ];

        $page['#attached']['library'][] = 'delphi/delphi-boxplot';


        return $page;


    }

    public function survey_get_average($testArrayOverall, $isRadioButton, $max) {
        $avg_array = array();
        $avg = (!empty($testArrayOverall) ? array_sum($testArrayOverall) / count($testArrayOverall) : 0);
        $avg = round($avg, 1);
        if ($isRadioButton) {
            $avg_array["absolut"] = $avg;
            $avg_array["procent"] = round($avg / $max * 100, 1);
        } else {
            $avg_array["absolut"] = $avg;
            $avg_array["procent"] = round($avg / $max * 100, 1);
        }
        return $avg_array;
    }
    public function survey_get_median($testArrayOverall, $isRadioButton, $max) {
        $temp = array();
        $n = sizeof($testArrayOverall);
        foreach ($testArrayOverall as $item) {
            array_push($temp, $item);
        }
        if ($isRadioButton) {
            if (sizeof($temp) % 2 == 0) {
                $median = 0.5 * ($temp[$n / 2 - 1] + $temp[($n / 2)]);
            } else {
                $median = $temp[($n) / 2];
            }
            $median_array["absolut"] = $median;
            $median_array["procent"] = round($median / $max * 100, 1);
        } else {
            if (sizeof($temp) % 2 == 0) {
                $median = 0.5 * ($temp[$n / 2 - 1] + $temp[($n / 2)]);
            } else {
                $median = $temp[($n) / 2];
            }
            $median_array["absolut"] = $median;
            $median_array["procent"] = round($median / $max * 100, 1);
        }
        return $median_array;
    }
    public function survey_get_first_quantil($testArrayOverall, $isRadioButton, $max) {
        $temp = array();
        $n = sizeof($testArrayOverall);
        foreach ($testArrayOverall as $item) {
            array_push($temp, $item);
        }
        $np = $n * 0.25;
        if($isRadioButton) {
            if ($np == round($np)) {
                $first_quantil = 0.5 * ($temp[$np-1] + $temp[$np]);
            } else {
                $np = ceil($np);
                $np = intval($np);
                $first_quantil = $temp[$np - 1];
            }
            $first_quantil_array["absolut"] = $first_quantil;
            $first_quantil_array["procent"] = round($first_quantil / $max * 100, 1);
        } else {
            if ($np == round($np)) {
                $first_quantil = 0.5 * ($temp[$np - 1] + $temp[$np]);
            } else {
                $np = ceil($np);
                $np = intval($np);
                $first_quantil = $temp[$np - 1];
            }
            $first_quantil_array["absolut"] = $first_quantil;
            $first_quantil_array["procent"] = round($first_quantil / $max * 100, 1);
        }
        return $first_quantil_array;
    }
    public function survey_get_third_quantil($testArrayOverall, $isRadioButton, $max) {
        $temp = array();
        $n = sizeof($testArrayOverall);
        foreach ($testArrayOverall as $item) {
            array_push($temp, $item);
        }
        $np = $n * 0.75;
        if($isRadioButton) {
            if ($np == round($np)) {
                $third_quantil = 0.5 * ($temp[$np - 1] + $temp[$np]);
            } else {
                $np = ceil($np);
                $np = intval($np);
                $third_quantil = $temp[$np - 1];
            }
            $third_quantil_array["absolut"] = $third_quantil;
            $third_quantil_array["procent"] = round($third_quantil / $max * 100, 1);
        } else {
            if ($np == round($np)) {
                $third_quantil = 0.5 * ($temp[$np - 1] + $temp[$np]);
            } else {
                $np = ceil($np);
                $np = intval($np);
                $third_quantil = $temp[$np - 1];
            }
            $third_quantil_array["absolut"] = $third_quantil;
            $third_quantil_array["procent"] = round($third_quantil / $max * 100, 1);
        }
        return $third_quantil_array;
    }

    public function _formatNumber($number) {
        if (is_numeric($number) && floor($number) != $number) {
            $number = number_format($number,1, ",", "");
        } else {
            $number = number_format($number,0);
        }
        return $number;
    }

}