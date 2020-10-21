<?php

use nueip\helpers\SecurityHelper;
use PHPUnit\Framework\TestCase;
use RobThree\Auth\TwoFactorAuth;

/**
 * Test for SecurityHelperTest - PHP Unit
 */
class SecurityHelperTest extends TestCase
{
    /**
     * test DeepHtmlspecialchars
     */
    public function testDeepHtmlspecialchars()
    {
        $template = [
            'test1' => [45678, -87654, 0.6524],
            'test2' => 'test2 <script> alert("Error"); </script>',
            'test3' => [
                null,
                'test3 <script> alert("Error"); </script>',
            ],
            'test4' => (object) [
                'test4 <script> alert("Error"); </script>',
                'key' => 'test4 <script> alert("Error"); </script>',
            ],
            'test5' => [
                true, false,
                1, 0,
            ],
        ];

        $expected = [
            'test1' => [
                0 => '45678',
                1 => '-87654',
                2 => '0.6524'
            ],
            'test2' => 'test2 &lt;script&gt; alert(&quot;Error&quot;); &lt;/script&gt;',
            'test3' => [
                0 => '',
                1 => 'test3 &lt;script&gt; alert(&quot;Error&quot;); &lt;/script&gt;',
            ],
            'test4' => (object) [
                0 => 'test4 &lt;script&gt; alert(&quot;Error&quot;); &lt;/script&gt;',
                'key' => 'test4 &lt;script&gt; alert(&quot;Error&quot;); &lt;/script&gt;',
            ],
            'test5' => [
                0 => '1',
                1 => '',
                2 => '1',
                3 => '0',
            ],
        ];

        $output = SecurityHelper::deepHtmlspecialchars($template);

        $this->assertEquals($expected, $output);
    }

    /**
     * @dataProvider isHttpsProvider
     */
    public function testIsHttps($data, $expected)
    {
        $ori_SERVER = $_SERVER;

        $_SERVER = $data;

        $this->assertEquals($expected, SecurityHelper::isHttps());

        $_SERVER = $ori_SERVER;
    }

    /**
     * test TotpSpace
     */
    public function testTFA()
    {
        $tfa = SecurityHelper::setTotpSpace('PHPunit');

        $this->assertInstanceOf(TwoFactorAuth::class, $tfa);

        $this->assertEquals($tfa, SecurityHelper::getTotpSpace());
    }

    public function isHttpsProvider()
    {
        return [
            [
                [
                    'HTTP_X_FORWARDED_PROTO' => 'HTTPS'
                ], true
            ],
            [
                [
                    'HTTP_FRONT_END_HTTPS' => 'ON'
                ], true
            ],
            [
                [
                    'HTTPS' => 'ON'
                ], true
            ],
            [
                [
                    'HTTP_X_FORWARDED_PROTO' => 'HTTPS',
                    'HTTP_FRONT_END_HTTPS' => 'OFF',
                    'HTTPS' => 'OFF',
                ], true
            ],
            [
                [
                    'HTTP_X_FORWARDED_PROTO' => 'HTTP',
                    'HTTP_FRONT_END_HTTPS' => 'ON',
                    'HTTPS' => 'ON',
                ], false
            ],
        ];
    }
}
