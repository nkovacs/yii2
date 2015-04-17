<?php

namespace yiiunit\framework\validators;

use DateTime;
use yii\validators\DateValidator;
use yiiunit\data\validators\models\FakedValidationModel;
use yiiunit\framework\i18n\IntlTestHelper;
use yiiunit\TestCase;
use IntlDateFormatter;

/**
 * @group validators
 */
class DateValidatorTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        IntlTestHelper::setIntlStatus($this);

        $this->mockApplication([
            'timeZone' => 'UTC',
            'language' => 'ru-RU',
        ]);
    }

    protected function tearDown()
    {
        parent::tearDown();
        IntlTestHelper::resetIntlStatus();
    }

    public function testEnsureMessageIsSet()
    {
        $val = new DateValidator;
        $this->assertTrue($val->message !== null && strlen($val->message) > 1);
    }

    public function testIntlValidateValue()
    {
        $this->testValidateValue();

        $this->mockApplication([
            'language' => 'en-GB',
            'components' => [
                'formatter' => [
                    'dateFormat' => 'short',
                ]
            ]
        ]);
        $val = new DateValidator();
        $this->assertTrue($val->validate('31/5/2017'));
        $this->assertFalse($val->validate('5/31/2017'));
        $val = new DateValidator(['format' => 'short', 'locale' => 'en-GB']);
        $this->assertTrue($val->validate('31/5/2017'));
        $this->assertFalse($val->validate('5/31/2017'));

        $this->mockApplication([
            'language' => 'de-DE',
            'components' => [
                'formatter' => [
                    'dateFormat' => 'short',
                ]
            ]
        ]);
        $val = new DateValidator();
        $this->assertTrue($val->validate('31.5.2017'));
        $this->assertFalse($val->validate('5.31.2017'));
        $val = new DateValidator(['format' => 'short', 'locale' => 'de-DE']);
        $this->assertTrue($val->validate('31.5.2017'));
        $this->assertFalse($val->validate('5.31.2017'));
    }

    public function testValidateValue()
    {
        // test PHP format
        $val = new DateValidator(['format' => 'php:Y-m-d']);
        $this->assertFalse($val->validate('3232-32-32'));
        $this->assertTrue($val->validate('2013-09-13'));
        $this->assertFalse($val->validate('31.7.2013'));
        $this->assertFalse($val->validate('31-7-2013'));
        $this->assertFalse($val->validate('20121212'));
        $this->assertFalse($val->validate('asdasdfasfd'));
        $this->assertFalse($val->validate('2012-12-12foo'));
        $this->assertFalse($val->validate(''));
        $this->assertFalse($val->validate(time()));
        $val->format = 'php:U';
        $this->assertTrue($val->validate(time()));
        $val->format = 'php:d.m.Y';
        $this->assertTrue($val->validate('31.7.2013'));
        $val->format = 'php:Y-m-!d H:i:s';
        $this->assertTrue($val->validate('2009-02-15 15:16:17'));

        // test ICU format
        $val = new DateValidator(['format' => 'yyyy-MM-dd']);
        $this->assertFalse($val->validate('3232-32-32'));
        $this->assertTrue($val->validate('2013-09-13'));
        $this->assertFalse($val->validate('31.7.2013'));
        $this->assertFalse($val->validate('31-7-2013'));
        $this->assertFalse($val->validate('20121212'));
        $this->assertFalse($val->validate('asdasdfasfd'));
        $this->assertFalse($val->validate('2012-12-12foo'));
        $this->assertFalse($val->validate(''));
        $this->assertFalse($val->validate(time()));
        $val->format = 'dd.MM.yyyy';
        $this->assertTrue($val->validate('31.7.2013'));
        $val->format = 'yyyy-MM-dd HH:mm:ss';
        $this->assertTrue($val->validate('2009-02-15 15:16:17'));
    }

    public function testIntlValidateAttributePHPFormat()
    {
        $this->testValidateAttributePHPFormat();
    }

    public function testValidateAttributePHPFormat()
    {
        // error-array-add
        $val = new DateValidator(['format' => 'php:Y-m-d']);
        $model = new FakedValidationModel;
        $model->attr_date = '2013-09-13';
        $val->validateAttribute($model, 'attr_date');
        $this->assertFalse($model->hasErrors('attr_date'));
        $model = new FakedValidationModel;
        $model->attr_date = '1375293913';
        $val->validateAttribute($model, 'attr_date');
        $this->assertTrue($model->hasErrors('attr_date'));
        //// timestamp attribute
        $val = new DateValidator(['format' => 'php:Y-m-d', 'timestampAttribute' => 'attr_timestamp']);
        $model = new FakedValidationModel;
        $model->attr_date = '2013-09-13';
        $model->attr_timestamp = true;
        $val->validateAttribute($model, 'attr_date');
        $this->assertFalse($model->hasErrors('attr_date'));
        $this->assertFalse($model->hasErrors('attr_timestamp'));
        $this->assertEquals(
             mktime(0, 0, 0, 9, 13, 2013), // 2013-09-13
//            DateTime::createFromFormat('Y-m-d', '2013-09-13')->getTimestamp(),
            $model->attr_timestamp
        );
        $val = new DateValidator(['format' => 'php:Y-m-d']);
        $model = FakedValidationModel::createWithAttributes(['attr_date' => []]);
        $val->validateAttribute($model, 'attr_date');
        $this->assertTrue($model->hasErrors('attr_date'));

    }

    public function testIntlValidateAttributeICUFormat()
    {
        $this->testValidateAttributeICUFormat();
    }

    public function testValidateAttributeICUFormat()
    {
        // error-array-add
        $val = new DateValidator(['format' => 'yyyy-MM-dd']);
        $model = new FakedValidationModel;
        $model->attr_date = '2013-09-13';
        $val->validateAttribute($model, 'attr_date');
        $this->assertFalse($model->hasErrors('attr_date'));
        $model = new FakedValidationModel;
        $model->attr_date = '1375293913';
        $val->validateAttribute($model, 'attr_date');
        $this->assertTrue($model->hasErrors('attr_date'));
        //// timestamp attribute
        $val = new DateValidator(['format' => 'yyyy-MM-dd', 'timestampAttribute' => 'attr_timestamp']);
        $model = new FakedValidationModel;
        $model->attr_date = '2013-09-13';
        $model->attr_timestamp = true;
        $val->validateAttribute($model, 'attr_date');
        $this->assertFalse($model->hasErrors('attr_date'));
        $this->assertFalse($model->hasErrors('attr_timestamp'));
        $this->assertEquals(
            mktime(0, 0, 0, 9, 13, 2013), // 2013-09-13
//            DateTime::createFromFormat('Y-m-d', '2013-09-13')->getTimestamp(),
            $model->attr_timestamp
        );
        $val = new DateValidator(['format' => 'yyyy-MM-dd']);
        $model = FakedValidationModel::createWithAttributes(['attr_date' => []]);
        $val->validateAttribute($model, 'attr_date');
        $this->assertTrue($model->hasErrors('attr_date'));
        $val = new DateValidator(['format' => 'yyyy-MM-dd']);
        $model = FakedValidationModel::createWithAttributes(['attr_date' => '2012-12-12foo']);
        $val->validateAttribute($model, 'attr_date');
        $this->assertTrue($model->hasErrors('attr_date'));
    }

    public function testIntlMultibyteString()
    {
        $val = new DateValidator(['format' => 'dd MMM yyyy', 'locale' => 'de_DE']);
        $model = FakedValidationModel::createWithAttributes(['attr_date' => '12 Mai 2014']);
        $val->validateAttribute($model, 'attr_date');
        $this->assertFalse($model->hasErrors('attr_date'));

        $val = new DateValidator(['format' => 'dd MMM yyyy', 'locale' => 'ru_RU']);
        $model = FakedValidationModel::createWithAttributes(['attr_date' => '12 мая 2014']);
        $val->validateAttribute($model, 'attr_date');
        $this->assertFalse($model->hasErrors('attr_date'));
    }

    public function testIntlValidateRange()
    {
        $this->testValidateValueRange();
    }

    public function testValidateValueRange()
    {
        $date = '14-09-13';
        $val = new DateValidator(['format' => 'yyyy-MM-dd']);
        $this->assertTrue($val->validate($date), "$date is valid");

        $val = new DateValidator(['format' => 'yyyy-MM-dd', 'min' => '1900-01-01']);
        $date = "1958-01-12";
        $this->assertTrue($val->validate($date), "$date is valid");

        $val = new DateValidator(['format' => 'yyyy-MM-dd', 'max' => '2000-01-01']);
        $date = '2014-09-13';
        $this->assertFalse($val->validate($date), "$date is too big");
        $date = "1958-01-12";
        $this->assertTrue($val->validate($date), "$date is valid");

        $val = new DateValidator(['format' => 'yyyy-MM-dd', 'min' => '1900-01-01', 'max' => '2000-01-01']);
        $this->assertTrue($val->validate('1999-12-31'), "max -1 day is valid");
        $this->assertTrue($val->validate('2000-01-01'), "max is inside range");
        $this->assertTrue($val->validate('1900-01-01'), "min is inside range");
        $this->assertFalse($val->validate('1899-12-31'), "min -1 day is invalid");
        $this->assertFalse($val->validate('2000-01-02'), "max +1 day is invalid");
    }

    private function validateModelAttribute($validator, $date, $expected, $message = '')
    {
        $model = new FakedValidationModel;
        $model->attr_date = $date;
        $validator->validateAttribute($model, 'attr_date');
        if (!$expected) {
            $this->assertTrue($model->hasErrors('attr_date'), $message);
        } else {
            $this->assertFalse($model->hasErrors('attr_date'), $message);
        }
    }

    public function testIntlValidateAttributeRange() {
        $this->testValidateAttributeRange();
    }

    public function testValidateAttributeRange()
    {
        $val = new DateValidator(['format' => 'yyyy-MM-dd']);
        $date = '14-09-13';
        $this->validateModelAttribute($val, $date, true, "$date is valid");

        $val = new DateValidator(['format' => 'yyyy-MM-dd', 'min' => '1900-01-01']);
        $date = '1958-01-12';
        $this->validateModelAttribute($val, $date, true, "$date is valid");

        $val = new DateValidator(['format' => 'yyyy-MM-dd', 'max' => '2000-01-01']);
        $date = '2014-09-13';
        $this->validateModelAttribute($val, $date, false, "$date is too big");
        $date = '1958-01-12';
        $this->validateModelAttribute($val, $date, true, "$date is valid");

        $val = new DateValidator(['format' => 'yyyy-MM-dd', 'min' => '1900-01-01', 'max' => '2000-01-01']);
        $this->validateModelAttribute($val, '1999-12-31', true, "max -1 day is valid");
        $this->validateModelAttribute($val, '2000-01-01', true, "max is inside range");
        $this->validateModelAttribute($val, '1900-01-01', true, "min is inside range");
        $this->validateModelAttribute($val, '1899-12-31', false, "min -1 day is invalid");
        $this->validateModelAttribute($val, '2000-01-02', false, "max +1 day is invalid");
    }

    public function testIntlValidateValueRangeOld()
    {
        if ($this->checkOldIcuBug()) {
            $this->markTestSkipped("ICU is too old.");
        }
        $date = '14-09-13';
        $val = new DateValidator(['format' => 'yyyy-MM-dd', 'min' => '1900-01-01']);
        $this->assertFalse($val->validate($date), "$date is too small");
    }

    public function testIntlValidateAttributeRangeOld()
    {
        if ($this->checkOldIcuBug()) {
            $this->markTestSkipped("ICU is too old.");
        }
        $date = '14-09-13';
        $val = new DateValidator(['format' => 'yyyy-MM-dd', 'min' => '1900-01-01']);
        $this->validateModelAttribute($val, $date, false, "$date is too small");
    }

    /**
     * returns true if the version of ICU is old and has a bug that makes it
     * impossible to parse two digit years properly.
     * see http://bugs.icu-project.org/trac/ticket/9836
     * @return boolean
     */
    private function checkOldIcuBug()
    {
        $date = '14';
        $formatter = new IntlDateFormatter('en-US', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'yyyy');
        $parsePos = 0;
        $parsedDate = @$formatter->parse($date, $parsePos);

        if (is_int($parsedDate) && $parsedDate > 0) {
            return true;
        }

        return false;
    }
}