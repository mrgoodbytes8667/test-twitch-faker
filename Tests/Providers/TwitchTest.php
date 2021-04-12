<?php

namespace Bytes\Common\Faker\Tests\Providers;

use Bytes\Common\Faker\Twitch\TestTwitchFakerTrait;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class TwitchTest
 * @package Bytes\Common\Faker\Tests\Providers
 */
class TwitchTest extends TestCase
{
    use TestTwitchFakerTrait;

    /**
     * Test is mostly for coverage, there isn't a good way to test this function
     * @dataProvider provideLoops
     * @param int $index
     */
    public function testId(int $index)
    {
        $words = $this->faker->id();
        $this->assertGreaterThanOrEqual(6, strlen($words));
    }

    /**
     * @return Generator
     */
    public function provideLoops()
    {
        foreach (range(1, 500) as $index) {
            yield [$index];
        }
    }

    /**
     * Test is mostly for coverage, there isn't a good way to test this function
     * @dataProvider provideLoops
     * @param int $index
     */
    public function testAccessToken(int $index)
    {
        $words = $this->faker->accessToken();
        $this->assertEquals(30, strlen($words));
    }

    /**
     * Test is mostly for coverage, there isn't a good way to test this function
     * @dataProvider provideLoops
     * @param int $index
     */
    public function testRefreshToken(int $index)
    {
        $words = $this->faker->refreshToken();
        $this->assertEquals(30, strlen($words));
    }

    /**
     *
     */
    public function testRateLimit()
    {
        foreach(range(1, 10) as $j) {
            foreach (range(-5, 65) as $index) {
                $result = $this->faker->rateLimit($index);
                $this->assertGreaterThanOrEqual(5, $result);
            }
        }
    }

    /**
     *
     */
    public function testRateLimitArray()
    {
        $result = $this->faker->rateLimitArray();
        $this->assertArrayHasKey('RateLimit-Limit', $result);
        $this->assertArrayHasKey('RateLimit-Remaining', $result);
        $this->assertArrayHasKey('RateLimit-Reset', $result);

        $result = $this->faker->rateLimitArray(true);
        $this->assertArrayHasKey('RateLimit-Limit', $result);
        $this->assertArrayHasKey('RateLimit-Remaining', $result);
        $this->assertEquals(0, $result['RateLimit-Remaining']);
        $this->assertArrayHasKey('RateLimit-Reset', $result);

    }
}