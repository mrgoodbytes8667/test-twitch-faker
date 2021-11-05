<?php

namespace Bytes\Common\Faker\Tests\Providers;

use Bytes\Common\Faker\Twitch\TestTwitchFakerTrait;
use Generator;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\LogicalOr;
use PHPUnit\Framework\Constraint\RegularExpression;
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
     * @return Generator
     */
    public function provideSmallLoops()
    {
        foreach (range(1, 3) as $index) {
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
        foreach (range(1, 10) as $j) {
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

    /**
     * @dataProvider provideLoops
     * @param int $index
     */
    public function testLogin($index)
    {
        $result = $this->faker->login();
        $this->assertMatchesRegularExpression('/^[a-z0-9_]{4,25}$/', $result);
    }

    /**
     * @dataProvider provideSmallLoops
     * @param int $index
     */
    public function testLoginWithArgs($index)
    {
        foreach (range(3, 26) as $minChars) {
            foreach (range(3, 26) as $maxChars) {
                $result = $this->faker->login($minChars, $maxChars);
                $this->assertMatchesRegularExpression('/^[a-z0-9_]{4,25}$/', $result);
            }
        }
    }

    /**
     * @dataProvider provideLoops
     * @param int $index
     */
    public function testDisplayName($index)
    {
        $result = $this->faker->displayName();
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_]{4,25}$/', $result);
    }

    /**
     * @dataProvider provideSmallLoops
     * @param int $index
     */
    public function testDisplayNameWithArgs($index)
    {
        foreach (range(3, 26) as $minChars) {
            foreach (range(3, 26) as $maxChars) {
                $result = $this->faker->displayName($minChars, $maxChars);
                $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_]{4,25}$/', $result);
            }
        }
    }

    /**
     * @dataProvider provideLoops
     * @param int $index
     */
    public function testBroadcasterType($index)
    {
        $result = $this->faker->broadcasterType();

        $logicalOr = new LogicalOr();
        $logicalOr->setConstraints([
            new RegularExpression('/^staff|admin|global_mod$/'),
            new IsEmpty(),
        ]);

        static::assertThat(
            $result,
            $logicalOr
        );
    }

    /**
     * @dataProvider provideLoops
     * @param int $index
     */
    public function testType($index)
    {
        $result = $this->faker->type();

        $logicalOr = new LogicalOr();
        $logicalOr->setConstraints([
            new RegularExpression('/^partner|affiliate$/'),
            new IsEmpty(),
        ]);

        static::assertThat(
            $result,
            $logicalOr
        );
    }

    /**
     * @dataProvider provideLoops
     * @param int $index
     */
    public function testStreamType($index)
    {
        $result = $this->faker->streamType();

        $logicalOr = new LogicalOr();
        $logicalOr->setConstraints([
            new IsEqual('live'),
            new IsEqual('playlist'),
            new IsEqual('watch_party'),
            new IsEqual('premiere'),
            new IsEqual('rerun'),
        ]);

        static::assertThat(
            $result,
            $logicalOr
        );
    }
}