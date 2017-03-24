<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\DAO\User;
use App\Models\DAO\AccountType;
use Illuminate\Support\Facades\DB;
use FastRoute\RouteParser\Std;

/**
 * User Repository.
 * @author silver.ibenye
 *
 */
class UserRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\User';
    }

    /**
     *
     * @param string $emailAddress
     * @return Model|null
     */
    public function getUserByEmailAddress($emailAddress)
    {
        return $this->getUserWhere('emailAddress', $emailAddress);
    }

    /**
     *
     *@param string $field
     * @param string $value
     * @return Model|null
     */
    public function getUserWhere($field, $value)
    {
        return $this->model->where($field, $value)->first();
    }

    /**
     *
     * @param integer $userId
     * @param array $attributeNames
     * @return array An array of StdClass
     */
    public function getUserAttributes($userId, $attributeNames = [], $attributeTypeId = NULL)
    {
        if (empty($attributeNames) && empty($attributeTypeId)) {
            $user = $this->get($userId);
            $userAttributes = $user->userAttributes;
            $userAttributesContent = array ();
            $i = 0;
            foreach ($userAttributes as $value) {
                $attrObj = new \stdClass();
                $attrObj->id = $value->id;
                $attrObj->attributeType = $value->attributeType->name;
                $attrObj->name = $value->name;
                $attrObj->displayName = $value->displayName;
                $attrObj->attributeValue = $value->pivot->attributeValue;

                $userAttributesContent [$i] = $attrObj;
                ++$i;
            }

            return $userAttributesContent;
        } else {
            $builder = DB::table('nsh_userattributes')->select('nsh_userattributes.id',
                    'nsh_userattributes.name', 'nsh_userattributes.displayName',
                    'nsh_userattributetypes.name as attributeType',
                    'nsh_userattributevalues.attributeValue')
                ->join('nsh_userattributetypes', 'nsh_userattributetypes.id', '=',
                    'nsh_userattributes.attributeTypeId')
                ->leftJoin('nsh_userattributevalues',
                    function ($join) use ($userId) {
                        $join->on('nsh_userattributevalues.userAttributeId', '=',
                                'nsh_userattributes.id')
                            ->where('nsh_userattributevalues.userId', '=', $userId);
                    });

            if (!empty($attributeNames) && !empty($attributeTypeId)) {
                $builder = $builder->whereIn('nsh_userattributes.name', $attributeNames)->orWhere(
                        'nsh_userattributes.attributeTypeId', '=', $attributeTypeId);
            } elseif (!empty($attributeNames)) {
                $builder = $builder->whereIn('nsh_userattributes.name', $attributeNames);
            } elseif (!empty($attributeTypeId)) {
                $builder = $builder->where('nsh_userattributes.attributeTypeId', '=',
                        $attributeTypeId);
            }

            return $builder->get();
        }
    }

    /**
     *
     * @param integer $userId
     * @param string $credentialType
     * @return Collection
     */
    public function getUserCredentials($userId, $credentialType = NULL)
    {
        $user = $this->get($userId);

        if (!empty($credentialType)) {
            $userCredntials = $user->credentialTypes->where('name', $credentialType);
            return $userCredntials;
        } else {
            $userCredntials = $user->credentialTypes;
            return $userCredntials;
        }
    }

    /**
     *
     * @param integer $userId
     * @param string $categoryIdsCollection
     * @return void
     */
    public function linkUserToCategory($userId, $categoryIdsCollection)
    {
        $user = $this->get($userId);

        $alreadyLinkedCategories = $user->categories;
        $alreadyLinkedCategoryIds = array ();

        foreach ($alreadyLinkedCategories as $alreadyLinkedCategory) {
            $alreadyLinkedCategoryIds [] = $alreadyLinkedCategory->id;
        }

        foreach ($categoryIdsCollection as $categoryId) {
            if (!in_array($categoryId, $alreadyLinkedCategoryIds)) {
                $user->categories()->attach($categoryId);
            }
        }
    }

    /**
     *
     * @param integer $userId
     * @param string $categoryIdsCollection
     * @return string
     */
    public function unlinkUserFromCategory($userId, $categoryIdsCollection)
    {
        $user = $this->get($userId);

        $alreadyLinkedCategories = $user->categories;
        $alreadyLinkedCategoryIds = array ();

        foreach ($alreadyLinkedCategories as $alreadyLinkedCategory) {
            $alreadyLinkedCategoryIds [] = $alreadyLinkedCategory->id;
        }

        foreach ($categoryIdsCollection as $categoryId) {
            if (in_array($categoryId, $alreadyLinkedCategoryIds)) {
                $user->categories()->detach($categoryId);
            }
        }
    }

    /**
     *
     * @param Model $user
     * @param array $attributesCollection
     * @return void
     */
    public function upsertUserAttributeValue(Model $user, $attributesCollection)
    {
        $userAttributes = $user->userAttributes;

        $existingAttributesCollection = array ();

        foreach ($userAttributes as $userAttribute) {
            $existingAttributesCollection [$userAttribute->id] = $userAttribute->pivot->attributeValue;
        }

        foreach ($attributesCollection as $userAttributeRequest) {
            if (in_array($userAttributeRequest ['attributeId'],
                    array_keys($existingAttributesCollection))) {
                // only update if it is different.
                if ($existingAttributesCollection [$userAttributeRequest ['attributeId']] !=
                         $userAttributeRequest ['attributeValue']) {
                    $user->userAttributes()->updateExistingPivot(
                            $userAttributeRequest ['attributeId'],
                            [
                                    'attributeValue' => $userAttributeRequest ['attributeValue']
                            ]);
                }
            } else {
                $user->userAttributes()->attach($userAttributeRequest ['attributeId'],
                        [
                                'attributeValue' => $userAttributeRequest ['attributeValue']
                        ]);
            }
        }
    }

    public function upsertUserCredential(Model $user, $credential)
    {
        $userCredentials = $user->credentialTypes->where('name', $credential ['credentialType'])->first();

        if (empty($userCredentials)) {
            $user->credentialTypes()->attach($credential ['credentialTypeId'],
                    [
                            'password' => $credential ['password']
                    ]);
        } else {
            $user->credentialTypes()->updateExistingPivot($credential ['credentialTypeId'],
                    [
                            'password' => $credential ['password']
                    ]);
        }
    }

    /**
     *
     * @param array $requestBody
     * @return Model
     */
    public function createUser($requestBody)
    {
        $userModelAttributes = array ();
        $userModelAttributes ['emailAddress'] = $requestBody ['emailAddress'];

        $user = $this->create($userModelAttributes);

        $user->credentialTypes()->attach($requestBody ['credentialTypeId'],
                [
                        'password' => $requestBody ['password']
                ]);

        $user->accountTypes()->attach($requestBody ['accountTypeId']);

        return $user;
    }

    public function addAccountType(User $user, AccountType $accountType)
    {
        $existingUserAccountType = $user->accountTypes->where('name', $accountType->name)->first();

        if (empty($existingUserAccountType)) {
            $user->accountTypes()->attach($accountType->id);
        }
    }
}

