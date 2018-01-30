<?php

namespace Ivoz\Provider\Domain\Model\User;

use Ivoz\Api\Core\Annotation\AttributeDefinition;

class UserDto extends UserDtoAbstract
{
    const CONTEXT_MY_PROFILE = 'myProfile';
    const CONTEXT_PUT_MY_PROFILE = 'updateMyProfile';

    const CONTEXT_TYPES = [
        self::CONTEXT_COLLECTION,
        self::CONTEXT_SIMPLE,
        self::CONTEXT_DETAILED,
        self::CONTEXT_MY_PROFILE,
        self::CONTEXT_PUT_MY_PROFILE
    ];

    /**
     * @var string
     * @AttributeDefinition(type="string", description="required in order to update user password")
     */
    protected $oldPass;

    /**
     * @return string
     */
    public function getOldPass()
    {
        return $this->oldPass;
    }

    /**
     * @param string $oldPass
     * @return UserDto
     */
    public function setOldPass($oldPass)
    {
        $this->oldPass = $oldPass;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getPropertyMap(string $context = '')
    {
        if ($context === self::CONTEXT_COLLECTION) {
            return [
                'id' => 'id',
                'name' => 'name',
                'lastname' => 'lastname',
            ];
        }

        if ($context === self::CONTEXT_MY_PROFILE) {
            return [
                'id' => 'id',
                'name' => 'name',
                'pass' => 'pass',
                'lastname' => 'lastname',
                'email' => 'email',
                'doNotDisturb' => 'doNotDisturb',
                'isBoss' => 'isBoss',
                'maxCalls' => 'maxCalls',
                'timezoneId' => 'timezone'
            ];
        }

        if ($context === self::CONTEXT_PUT_MY_PROFILE) {
            return [
                'name' => 'name',
                'pass' => 'pass',
                'oldPass' => 'oldPass',
                'lastname' => 'lastname',
                'email' => 'email',
                'doNotDisturb' => 'doNotDisturb',
                'isBoss' => 'isBoss',
                'maxCalls' => 'maxCalls'
            ];
        }

        return parent::getPropertyMap(...func_get_args());
    }

    /**
     * @inheritdoc
     */
    public function normalize(string $context)
    {
        $response = parent::normalize(...func_get_args());
        $response['pass'] = '*****';

        return $response;
    }

    /**
     * @inheritdoc
     */
    public function denormalize(array $data, string $context)
    {
        if (isset($data['oldPass'])) {
            $this->setOldPass($data['oldPass']);
        } else {
            unset($data['pass']);
        }

        return parent::denormalize($data, $context);
    }
}


