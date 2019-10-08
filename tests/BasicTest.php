<?php namespace Test;

use SnowReportKit\SnowReportKit\Attribute\Address;
use SnowReportKit\SnowReportKit\Attribute\Camera;
use SnowReportKit\SnowReportKit\Attribute\Event;
use SnowReportKit\SnowReportKit\Attribute\Hours;
use SnowReportKit\SnowReportKit\Attribute\Info;
use SnowReportKit\SnowReportKit\Attribute\Lift;
use SnowReportKit\SnowReportKit\Attribute\Message;
use SnowReportKit\SnowReportKit\Attribute\Park;
use SnowReportKit\SnowReportKit\Attribute\Road;
use SnowReportKit\SnowReportKit\Attribute\Season;
use SnowReportKit\SnowReportKit\Attribute\Special;
use SnowReportKit\SnowReportKit\Attribute\Trail;
use SnowReportKit\SnowReportKit\Attribute\Vertical;
use SnowReportKit\SnowReportKit\MissingPropertyException;
use SnowReportKit\SnowReportKit\Mountain;
use SnowReportKit\SnowReportKit\PropertyValueException;
use SnowReportKit\SnowReportKit\SRK;

class BasicTest extends TestBase
{

    public $out = [];


    public function testBareMinimum() : void
    {
        $data = [
            'meta' => [
                'version' => 'v1',
            ],
            'info' => [
                'name' => 'Acme Ski Resort',
            ],
        ];

        $this->assertEquals($data, Mountain::make('Acme Ski Resort')->toArray());
        $this->assertEquals($data, Mountain::make([
            'info' => [
                'name' => 'Acme Ski Resort',
            ],
        ])->toArray());

        $this->assertEquals(json_encode($data, JSON_PRETTY_PRINT), Mountain::make('Acme Ski Resort'));

        $this->write(Mountain::make('Acme Ski Resort')->toArray(), __METHOD__);
    }


    public function testJsonOutput() : void
    {
        $data = json_encode([
            'meta' => [
                'version' => 'v1',
            ],
            'info' => [
                'name' => 'Acme Ski Resort',
            ],
        ], JSON_PRETTY_PRINT);

        $this->assertEquals($data, Mountain::make('Acme Ski Resort')->toJson());
    }


    public function testGet() : void
    {
        $mountain = Mountain::make('Acme Ski Resort');
        $this->assertEquals('Acme Ski Resort', $mountain->get('info.name'));
    }


    public function testSet() : void
    {
        $mountain = Mountain::make('Acme Ski Resort');
        $mountain->set('info.phone', '1231231233');

        $this->assertArraySubset([
            'info' => [
                'phone' => '1231231233',
            ],
        ], $mountain->toArray());

        $mountain->set('test_a', 'does not exist');
        $mountain->set('info.test_b', 'does not exist');
        $this->assertArrayNotHasKey('test_a', $mountain->toArray());
        $this->assertArrayNotHasKey('test_b', $mountain->toArray()[ 'info' ]);
    }


    public function testRequired() : void
    {
        $mountain = Mountain::make(['info' => ['name' => null]]);

        $this->expectException(MissingPropertyException::class);
        $mountain->toArray();
    }


    public function testFullInfo() : void
    {
        $data = [
            'info' => [
                'name'                 => 'Acme Ski Resort',
                'website'              => 'https://acmeskiresort.com',
                'address'              => [
                    'address'    => '1234 Acme Rd',
                    'state'      => 'Montana',
                    'city'       => 'Bozeman',
                    'country'    => 'USA',
                    'zip'        => 12345,
                    'directions' => [
                        'From airport head east on interstate, take exit 123, turn left onto...',
                        'From University of School, head north on University Drive...',
                    ],
                ],
                'phone'                => '1-866-123-1234',
                'open'                 => true,
                'acres'                => 2000,
                'max_vertical'         => 2700,
                'lift_served_vertical' => 2600,
                'top_elevation'        => 8800,
                'base_elevation'       => 6100,
                'acres_open'           => 500,
                'hours'                => [
                    'open_time'  => '09:00',
                    'close_time' => '16:30',
                ],
                'season'               => [
                    'open'  => '2019-01-01',
                    'close' => '2019-01-01',
                ],
            ],
        ];

        $mountain = Mountain::make($data);

        $this->assertInstanceOf(Info::class, $mountain->get('info'));
        $this->assertInstanceOf(Address::class, $mountain->get('info.address'));
        $this->assertInstanceOf(Hours::class, $mountain->get('info.hours'));
        $this->assertInstanceOf(Season::class, $mountain->get('info.season'));
        $this->assertIsString($mountain->get('info.name'));
        $this->assertIsBool($mountain->get('info.open'));
        $this->assertIsInt($mountain->get('info.acres'));

        $this->assertArraySubset($data, $mountain->toArray());

        $this->write($mountain->toArray(), __METHOD__);
    }


    public function testMessages() : void
    {
        $data     = [
            'info' => [
                'name'     => '__mountain_name__',
                'messages' => [
                    [
                        'message' => '__info_text__',
                        'level'   => SRK::INFO,
                    ],
                    [
                        'message' => '__critical_text__',
                        'level'   => SRK::CRITICAL,
                    ],
                ],
            ],
        ];
        $mountain = Mountain::make($data);

        $this->assertInstanceOf(Info::class, $mountain->get('info'));
        $this->assertInstanceOf(Message::class, $mountain->get('info.messages')[ 0 ]);
        $this->assertArraySubset($data, $mountain->toArray());

        $this->expectException(PropertyValueException::class);
        Mountain::make([
            'info' => [
                'name'     => '__mountain_name__',
                'messages' => [
                    [
                        'message' => '__info_text__',
                        'level'   => '__enum_fails__',
                    ],
                ],
            ],
        ])->toArray();

        $this->expectException(MissingPropertyException::class);
        Mountain::make([
            'info' => [
                'name'     => '__mountain_name__',
                'messages' => [
                    [
                        'level' => Message::CRITICAL,
                    ],
                ],
            ],
        ])->toArray();
    }


    public function testHours() : void
    {
        $mountain = Mountain::make([
            'info' => [
                'name'  => 'Acme Ski Area',
                'hours' => [
                    'open_time'  => '09:00',
                    'close_time' => '16:30',
                ],
            ],
        ]);

        $this->assertArraySubset([
            'info' => [
                'hours' => [
                    'open_time'  => '09:00',
                    'close_time' => '16:30',
                ],
            ],
        ], $mountain->toArray());

        $mountain = Mountain::make([
            'info' => [
                'name'  => 'Acme Ski Area',
                'hours' => [
                    'open_time'       => '09:00',
                    'close_time'      => '16:30',
                    'night_open_time' => '17:00',
                ],
            ],
        ]);

        $this->assertArraySubset([
            'info' => [
                'hours' => [
                    'open_time'       => '09:00',
                    'close_time'      => '16:30',
                    'night_open_time' => '17:00',
                ],
            ],
        ], $mountain->toArray());

        $this->assertInstanceOf(Hours::class, $mountain->get('info.hours'));

        $this->write($mountain->toArray(), __METHOD__);

        $this->expectException(MissingPropertyException::class);
        Mountain::make([
            'info' => [
                'name'  => 'Acme Ski Area',
                'hours' => [
                    'close_time'      => '16:30',
                    'night_open_time' => '17:00',
                ],
            ],
        ])->toArray();
    }


    public function testSeason() : void
    {
        $data = [
            'info' => [
                'name'   => 'Acme Ski Area',
                'season' => [
                    'open'  => '2019-01-01',
                    'close' => '2019-01-01',
                ],
            ],
        ];

        $mountain = Mountain::make($data);
        $this->assertInstanceOf(Season::class, $mountain->get('info.season'));
        $this->assertArraySubset([
            'info' => [
                'season' => [
                    'open'  => '2019-01-01',
                    'close' => '2019-01-01',
                ],
            ],
        ], $mountain->toArray());

        $this->write($mountain->toArray(), __METHOD__);

        $this->expectException(MissingPropertyException::class);
        $mountain->set('info.season.open', null);
        $mountain->set('info.season.close', null);
        $mountain->toArray();
    }


    public function testRoads() : void
    {
        $roads = [
            'i80'           => [
                'open'         => true,
                'requirements' => '__requirements__',
                'message'      => '__message__',
            ],
            'Mountain Road' => [
                'open'    => true,
                'message' => '__message__',
            ],
            'Service Road'  => [
                'open'         => false,
                'requirements' => '__requirements__',
                'message'      => '__message__',
            ],
        ];

        $data = $this->withName(compact('roads'));

        $mountain = Mountain::make($data);

        $this->assertArraySubset(compact('roads'), $mountain->toArray());
        $this->assertInstanceOf(Road::class, $mountain->get('roads', 'i80'));
        $this->assertInstanceOf(Road::class, $mountain->get('roads', 'Mountain Road'));
        $this->assertInstanceOf(Road::class, $mountain->get('roads', 'Service Road'));

        $this->write($mountain->toArray(), __METHOD__);

        $this->expectException(MissingPropertyException::class);
        Mountain::make($this->withName([
            'roads' => [
                '__fail__' => [
                    'message' => '__no_open__',
                ],
            ],
        ]))->toArray();
    }


    public function testLifts() : void
    {
        $lifts    = [
            'lift_a' => [
                'status'          => SRK::OPEN,
                'persons'         => '__persons__',
                'night_operation' => false,
                'area'            => '__area__',
                'beacon_required' => true,
            ],
            'lift_b' => [
                'status' => SRK::CLOSED,
                'area'   => '__area__',
            ],
        ];
        $data     = $this->withName(compact('lifts'));
        $mountain = Mountain::make($data);

        $this->write($mountain->toArray(), __METHOD__);

        $this->assertInstanceOf(Lift::class, $mountain->get('lifts', 'lift_a'));
        $this->assertInstanceOf(Lift::class, $mountain->get('lifts', 'lift_b'));

        $this->expectException(PropertyValueException::class);
        Mountain::make($this->withName([
            'lifts' => [
                'lift_c' => [
                    'status' => '__enum_will_fail__',
                ],
            ],
        ]))->toArray();
    }


    public function testCameras() : void
    {
        $cameras  = [
            'camera_a' => [
                'url'      => '__url__',
                'area'     => '__area__',
                'live'     => true,
                'featured' => false,
            ],
            'camera_b' => [
                'url'      => '__url__',
                'area'     => '__area__',
                'live'     => true,
                'featured' => false,
            ],
        ];
        $data     = $this->withName(compact('cameras'));
        $mountain = Mountain::make($data);

        $this->assertInstanceOf(Camera::class, $mountain->get('cameras', 'camera_a'));
        $this->assertInstanceOf(Camera::class, $mountain->get('cameras', 'camera_b'));

        $this->write($mountain->toArray(), __METHOD__);

        $this->expectException(MissingPropertyException::class);
        Mountain::make($this->withName([
            'cameras' => [
                'camera_c' => [
                    'area' => '__fail_no_url_given__',
                ],
            ],
        ]))->toArray();
    }


    public function testTrails() : void
    {
        $trails   = [
            'trail_a' => [
                'open'            => true,
                'type'            => SRK::DOWNHILL,
                'groomed'         => SRK::AM,
                'snow_making'     => false,
                'night_operation' => false,
                'difficulty'      => SRK::GREEN,
                'area'            => '__area__',
                'featured'        => false,
            ],
            'trail_b' => [
                'open'       => false,
                'type'       => SRK::DOWNHILL,
                'groomed'    => SRK::UNGROOMED,
                'difficulty' => SRK::BLACK,
            ],
        ];
        $data     = $this->withName(compact('trails'));
        $mountain = Mountain::make($data);

        $this->assertInstanceOf(Trail::class, $mountain->get('trails', 'trail_a'));
        $this->assertInstanceOf(Trail::class, $mountain->get('trails', 'trail_b'));

        $this->write($mountain->toArray(), __METHOD__);

        $this->expectException(MissingPropertyException::class);
        Mountain::make($this->withName([
            'trails' => [
                'trail_c' => [
                    'open' => false,
                    'type' => SRK::DOWNHILL,
                ],
            ],
        ]))->toArray();

        $this->expectException(PropertyValueException::class);
        Mountain::make($this->withName([
            'trails' => [
                'trail_c' => [
                    'open'       => false,
                    'type'       => SRK::DOWNHILL,
                    'groomed'    => SRK::UNGROOMED,
                    'difficulty' => '__enum_fail__',
                ],
            ],
        ]))->toArray();

        $this->expectException(PropertyValueException::class);
        Mountain::make($this->withName([
            'trails' => [
                'trail_c' => [
                    'open'       => false,
                    'type'       => SRK::DOWNHILL,
                    'groomed'    => '__enum_fail__',
                    'difficulty' => SRK::GREEN,
                ],
            ],
        ]))->toArray();
    }


    public function testCalendar() : void
    {
        $events = [
            'event_a' => [
                'date'        => '2019-01-01',
                'summary'     => '__summary__',
                'description' => '__description__',
                'url'         => '__url__',
                'thumbnail'   => '__thumbnail__',
            ],
            'event_b' => [
                'date'        => '2019-01-02',
                'summary'     => '__summary__',
                'description' => '__description__',
                'url'         => '__url__',
                'thumbnail'   => '__thumbnail__',
            ],
        ];

        $data     = $this->withName(compact('events'));
        $mountain = Mountain::make($data);

        $this->assertInstanceOf(Event::class, $mountain->get('events', 'event_a'));
        $this->assertInstanceOf(Event::class, $mountain->get('events', 'event_b'));

        $this->write($mountain->toArray(), __METHOD__);

        $this->expectException(MissingPropertyException::class);
        Mountain::make($this->withName([
            'trails' => [
                'event_c' => [
                    'thumbnail' => '__missing_date__',
                ],
            ],
        ]))->toArray();
    }


    public function testFreestyle() : void
    {
        $parks     = [
            'park_a' => [
                'difficulty'      => SRK::L,
                'groomed'         => SRK::UNGROOMED,
                'open'            => true,
                'night_operation' => true,
                'area'            => '__area__',
                'featured'        => false,
                'features'        => [
                    'feature_a' => [
                        'open'       => true,
                        'type'       => SRK::DOWNHILL,
                        'difficulty' => SRK::L,
                        'details'    => '__details__',
                    ],
                    'feature_b' => [
                        'open'       => false,
                        'type'       => SRK::DOWNHILL,
                        'difficulty' => SRK::L,
                        'details'    => '__details__',
                    ],
                ],
            ],
            'park_b' => [
                'difficulty' => SRK::M,
                'groomed'    => SRK::UNGROOMED,
                'open'       => true,
                'area'       => '__area__',
                'features'   => [
                    'feature_a' => [
                        'open'       => true,
                        'type'       => SRK::DOWNHILL,
                        'difficulty' => SRK::L,
                        'details'    => '__details__',
                    ],
                ],
            ],
        ];
        $verticals = [
            'vertical_a' => [
                'open'            => true,
                'difficulty'      => SRK::L,
                'groomed'         => SRK::AM,
                'night_operation' => false,
                'area'            => '__area__',
                'featured'        => false,
            ],
            'vertical_b' => [
                'open'       => false,
                'difficulty' => SRK::L,
                'featured'   => false,
            ],
        ];
        $specials  = [
            'special_a' => [
                'open'       => false,
                'difficulty' => SRK::S,
                'area'       => '__area__',
            ],
            'special_b' => [
                'open'       => true,
                'difficulty' => SRK::S,
                'area'       => '__area__',
            ],
        ];

        $data     = $this->withName([
            'freestyle' => [
                'parks'     => $parks,
                'verticals' => $verticals,
                'specials'  => $specials,
            ],
        ]);
        $mountain = Mountain::make($data);

        $this->write($mountain->toArray(), __METHOD__);

        $this->assertInstanceOf(Park::class, $mountain->get('freestyle.parks', 'park_a'));
        $this->assertInstanceOf(Park::class, $mountain->get('freestyle.parks', 'park_b'));
        $this->assertInstanceOf(Vertical::class, $mountain->get('freestyle.verticals', 'vertical_a'));
        $this->assertInstanceOf(Vertical::class, $mountain->get('freestyle.verticals', 'vertical_b'));
        $this->assertInstanceOf(Special::class, $mountain->get('freestyle.specials', 'special_a'));
        $this->assertInstanceOf(Special::class, $mountain->get('freestyle.specials', 'special_b'));
    }


}
