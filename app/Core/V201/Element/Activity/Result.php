<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;

/**
 * Class Result
 * @package app\Core\V201\Element\Activity
 */
class Result extends BaseElement
{
    /**
     * @return result form path
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\Results';
    }

    /**
     * @return result repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Result');
    }

    /**
     * @param $results
     * @return array
     */
    public function getXmlData($results)
    {
        $resultData = [];

        foreach ($results as $totalResult) {
            $result       = $totalResult->result;
            $resultData[] = [
                '@attributes' => [
                    'type'               => $result['type'],
                    'aggregation-status' => $result['aggregation_status']
                ],
                'title'       => [
                    'narrative' => $this->buildNarrative($result['title'][0]['narrative'])
                ],
                'description' => [
                    'narrative' => $this->buildNarrative($result['description'][0]['narrative'])
                ],
                'indicator'   => [
                    '@attributes' => [
                        'measure'   => $result['indicator'][0]['measure'],
                        'ascending' => $result['indicator'][0]['ascending']
                    ],
                    'title'       => [
                        'narrative' => $this->buildNarrative($result['indicator'][0]['title'][0]['narrative'])
                    ],
                    'description' => [
                        'narrative' => $this->buildNarrative($result['indicator'][0]['description'][0]['narrative'])
                    ],
                    'baseline'    => [
                        '@attributes' => [
                            'year'  => $result['indicator'][0]['baseline'][0]['year'],
                            'value' => $result['indicator'][0]['baseline'][0]['value']
                        ],
                        'comment'     => [
                            'narrative' => $this->buildNarrative($result['indicator'][0]['baseline'][0]['comment'][0]['narrative'])
                        ]
                    ],
                    'period'      => [
                        'period-start' => [
                            '@attributes' => [
                                'iso-date' => $result['indicator'][0]['period'][0]['period_start'][0]['date']
                            ]
                        ],
                        'period-end'   => [
                            '@attributes' => [
                                'iso-date' => $result['indicator'][0]['period'][0]['period_end'][0]['date']
                            ]
                        ],
                        'target'       => [
                            '@attributes' => [
                                'value' => $result['indicator'][0]['period'][0]['target'][0]['value']
                            ],
                            'comment'     => [
                                'narrative' => $this->buildNarrative($result['indicator'][0]['period'][0]['target'][0]['comment'][0]['narrative'])
                            ]
                        ],
                        'actual'       => [
                            '@attributes' => [
                                'value' => $result['indicator'][0]['period'][0]['actual'][0]['value']
                            ],
                            'comment'     => [
                                'narrative' => $this->buildNarrative($result['indicator'][0]['period'][0]['actual'][0]['comment'][0]['narrative'])
                            ]
                        ]
                    ]
                ]
            ];
        }

        return $resultData;
    }
}
