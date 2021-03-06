<?php
/**
 * Created by PhpStorm.
 * Developer: RonySilva
 * GitHub: github.com/ronysilvati
 * Email: ronysilvati@live.com
 * Date: 25/05/18
 * Time: 07:04
 */

declare(strict_types=1);

require __DIR__.'/../src/JdGeolocation.class.php';

use PHPUnit\Framework\TestCase;
use GWSoft\JdGeolocation;

final class JdGeolocationTest extends TestCase{

    // Put here your google maps api key
    private $googleApiKey = '';

    public function testIsValidPoint(){

        $geo = new JdGeolocation($this->googleApiKey);

        $this->assertTrue($geo->isValidPoint(['lat'=>-9.45,'lng'=>4.453]));
    }

    public function testConvertPointToStringValid(){
        $geo = new JdGeolocation($this->googleApiKey);

        $this->assertEquals('9.45,4.453',$geo->convertPointToString(['lat'=>9.45,'lng'=>4.453]));
    }


    public function testCalcRouteWithOneWayPoint(){
        $geo = new JdGeolocation($this->googleApiKey);
        $originPoint    = [
            'lat'   =>  -9.660778,
            'lng'   =>  -35.741890
        ];

        $destionationPoint  = [
            'lat'   =>  -9.664014,
            'lng'   =>  -35.731930
        ];

        $waypoints  = [
            [
                'lat'   =>  -9.666242,
                'lng'   =>  -35.738507
            ]
        ];

        $this->assertEquals([
            'status'    =>  'OK',
            'distance' => 3618,//Meters
            'time'  => 746,// Seconds
            'overview_polyline'  =>  'zz}y@vycyEcDT`@l@dAxADCLE`BKpCS`G]~Ea@`AID}@JeDDgB`@?^AREt@{ArBwDxDkH[wAGY^IlAa@p@S_@cBScAw@yEuDhByC~AaBaDo@gAu@q@gC_Cy@w@g@m@Ui@Y[eDqDsBdBaAt@_@gASc@QMLe@DeACm@Uu@IGKCUUsA{@k@k@Se@K{@@i@Jq@Ze@VQTKrASzAQlBK^ZVZz@rAd@p@b@x@LHJPrBrCvCjE'
        ],$geo->calcRoute($originPoint,$destionationPoint,$waypoints));
    }


    public function testCalcRouteWithoutWayPoint(){
        $geo = new JdGeolocation($this->googleApiKey);
        $originPoint    = [
            'lat'   =>  -9.660778,
            'lng'   =>  -35.741890
        ];

        $destionationPoint  = [
            'lat'   =>  -9.664014,
            'lng'   =>  -35.731930
        ];


        $this->assertEquals([
            'status'    =>  'OK',
            'distance' => 3042,//Meters
            'time'  => 583, // Seconds
            'overview_polyline'  =>  'zz}y@vycyEcDT`@l@dAxADCLE`BKpCSfEWUgAq@aD_AmEm@iD_@yB_@iBOs@OFwElBgB{DuAkCASSe@GK@OTOdE}ADIDGg@eCe@gC`AOPKQ_@q@kAy@qAUc@AODOHIhD_C^o@Le@DeACm@Uu@IGKCUUsA{@k@k@Se@K{@@i@Jq@Ze@VQTKrASzAQlBK^ZVZz@rAd@p@b@x@LHJPrBrCvCjE'
        ],$geo->calcRoute($originPoint,$destionationPoint));
    }


    public function testCalcRouteWithoutResults(){
        $geo = new JdGeolocation($this->googleApiKey);
        $originPoint    = [
            'lat'   =>  -9324.6607342378,
            'lng'   =>  -35.741890
        ];

        $destionationPoint  = [
            'lat'   =>  -92343.6324364014,
            'lng'   =>  -35343.73431930
        ];

        $waypoints  = [
            [
                'lat'   =>  -934.666242,
                'lng'   =>  -353234.7384343507
            ]
        ];

        $this->assertEquals([
            'status'    =>  'NOT_FOUND',
            'distance' => 0,//Meters
            'time'  => 0,// Seconds
            'overview_polyline' => null
        ],$geo->calcRoute($originPoint,$destionationPoint,$waypoints));
    }


    public function testCalcRouteWithManyWayPoint(){
        $geo = new JdGeolocation($this->googleApiKey);
        $originPoint    = [
            'lat'   =>  -9.660778,
            'lng'   =>  -35.741890
        ];

        $destionationPoint  = [
            'lat'   =>  -9.664014,
            'lng'   =>  -35.731930
        ];

        $waypoints  = [
            [
                'lat'   =>  -9.666242,
                'lng'   =>  -35.738507
            ],
            [
                'lat'   =>  -9.630918,
                'lng'   =>  -35.742668
            ]
        ];


        $this->assertEquals([
            'status'    =>  'OK',
            'distance' => 12260,//Meters
            'time'  => 1969, // Seconds
            'overview_polyline'    => 'zz}y@vycyEcDT`@l@dAxADCLE`BKpCS`G]~Ea@`AID}@JeDDgB`@?^AREt@{ArBwDxDkH[wAGY^IlAa@p@S_@cBScAw@yEuDhByC~AaBaDo@gAu@q@a@]ODc@JoCbA_ExAkBn@KAIE}@y@I@y@_@k@WOEIQQ]g@{@_BqC_@g@]GmAXiCr@IMUBiAEsEOwGSiFS_HEiFGuBRa@B}BX}APsC\kGv@mDh@cCXyKrAmCRwFn@_Gr@mFp@uBTqGz@_BVuAJuAJ_Ef@wDb@yGt@oIbAmAL{@Hk@eCgI`AgC\~@~BTb@|@Kl@KJd@T`Ap@pCVx@K~@dBnFx@~BhBxFiByFq@gBrAg@pBq@|FoBvB{@~B_@xAUTK`BkAtBqDdB_Ez@iC`AObDYrDi@xFu@|Dc@zGw@lFk@hEc@fD_@~Fq@pFo@rBc@j@GfAMpFm@d@I|ASd@@TDj@Vr@PPDlGFvBDh@@v@R`Cn@tBt@dDpAZJZLx@@~AAb@ApDEVEdBYlDe@hCa@`AOPKQ_@q@kAy@qAUc@AODOHIhD_C^o@Le@DeACm@Uu@IGKCUUsA{@k@k@Se@K{@@i@Jq@Ze@VQTKrASzAQlBK^ZVZz@rAd@p@b@x@LHJPrBrCvCjE'
        ],$geo->calcRoute($originPoint,$destionationPoint,$waypoints));

    }

    public function testIsValidWayPoints(){
        $geo = new JdGeolocation($this->googleApiKey);
        $waypoints  = [
            [
                'lat'   =>  3.44,
                'lng'   =>  -4.33
            ],
            [
                'lat'   =>  3.23,
                'lng'   =>  4.53
            ]
        ];

        $this->assertTrue($geo->isValidWayPoints($waypoints));

    }

    public function testConvertWaypointsToString(){
        $geo = new JdGeolocation($this->googleApiKey);
        $waypoints  = [
            [
                'lat'   =>  3.44,
                'lng'   =>  -4.33
            ],
            [
                'lat'   =>  3.23,
                'lng'   =>  4.53
            ]
        ];

        $this->assertEquals('3.44,-4.33|3.23,4.53',$geo->convertWaypointsToString($waypoints));

    }

}
