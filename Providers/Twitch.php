<?php


namespace Bytes\Common\Faker\Providers;


use Faker\Generator;
use Faker\Provider\Address;
use Faker\Provider\Barcode;
use Faker\Provider\Base;
use Faker\Provider\Biased;
use Faker\Provider\Color;
use Faker\Provider\Company;
use Faker\Provider\DateTime;
use Faker\Provider\File;
use Faker\Provider\HtmlLorem;
use Faker\Provider\Image;
use Faker\Provider\Internet;
use Faker\Provider\Lorem;
use Faker\Provider\Medical;
use Faker\Provider\Miscellaneous;
use Faker\Provider\Payment;
use Faker\Provider\Person;
use Faker\Provider\PhoneNumber;
use Faker\Provider\Text;
use Faker\Provider\UserAgent;
use Faker\Provider\Uuid;

/**
 * Class Twitch
 * @package Bytes\Common\Faker\Providers
 *
 * @property Generator|MiscProvider|Address|Barcode|Biased|Color|Company|DateTime|File|HtmlLorem|Image|Internet|Lorem|Medical|Miscellaneous|Payment|Person|PhoneNumber|Text|UserAgent|Uuid $generator
 */
class Twitch extends Base
{
    use SetupDependenciesTrait;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        self::addProviderIfNeeded(MiscProvider::class, $generator);
        parent::__construct($generator);
    }

    /**
     * @param int $minChars
     * @return string
     */
    public function id(int $minChars = 6): string
    {
        $text = '';
        do {
            $text .= (string)$this->generator->numberBetween(1, 999999);
        } while (strlen($text) <= $minChars);

        return $text;
    }

    //region Token

    /**
     * @return string
     */
    public function refreshToken(): string
    {
        return self::accessToken();
    }

    /**
     * @return string
     */
    public function accessToken(): string
    {
        return $this->generator->randomAlphanumericString(30);
    }
    //endregion

    //region Mock Rate Limits

    /**
     * @param bool $noRemaining
     * @return array
     */
    public function rateLimitArray(bool $noRemaining = false): array
    {
        $reset = $this->generator->dateTimeInInterval('now', '+3 seconds')->getTimestamp();

        $info = [
            'RateLimit-Limit' => self::rateLimit(),
            'RateLimit-Remaining' => 4,
            'RateLimit-Reset' => $reset,
        ];
        if ($noRemaining) {
            $info['RateLimit-Remaining'] = 0;
        } else {
            $info['RateLimit-Remaining'] = self::rateLimit($info['RateLimit-Limit']);
        }

        return $info;
    }

    /**
     * @return int
     */
    public function rateLimit(int $max = 60)
    {
        if($max < 5) {
            $max = 5;
        }
        return $this->generator->numberBetween(5, $max);
    }
    //endregion

    /**
     * @return string = ['live', 'playlist', 'watch_party', 'premiere', 'rerun'][$any]
     */
    public function streamType()
    {
        return $this->generator->randomElement(['live', 'playlist', 'watch_party', 'premiere', 'rerun']);
    }

}