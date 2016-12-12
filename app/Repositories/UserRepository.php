<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Model
     */
    public function getUserByEmailAddress($emailAddress)
    {
        return $this->model->where('emailAddress', $emailAddress)->first();
    }

    /**
     *
     * @param string $userId
     * @param array $attributeNames
     * @return Collection
     */
    public function getUserAttributes($userId, $attributeNames = [])
    {
        $user = $this->get($userId);

        if (!empty($attributeNames)) {
            $userAttributes = $user->userAttributes->whereIn('name', $attributeNames);
            return $userAttributes;
        } else {
            $userAttributes = $user->userAttributes;
            return $userAttributes;
        }
    }

    /**
     *
     * @param string $userId
     * @param string $categoriesCollection
     * @return void
     */
    public function linkUserToCategory($userId, $categoriesCollection)
    {
        $user = $this->get($userId);

        $alreadyLinkedCategories = $user->categories;
        $alreadyLinkedCategoryIds = array ();

        foreach ($alreadyLinkedCategories as $alreadyLinkedCategory) {
            $alreadyLinkedCategoryIds [] = $alreadyLinkedCategory->id;
        }

        foreach ($categoriesCollection as $category) {
            if (!in_array($category ['categoryId'], $alreadyLinkedCategoryIds)) {
                $user->categories()->attach($category ['categoryId']);
            }
        }
    }

    /**
     *
     * @param string $userId
     * @param string $categoriesCollection
     * @return string
     */
    public function unlinkUserFromCategory($userId, $categoriesCollection)
    {
        $user = $this->get($userId);

        $alreadyLinkedCategories = $user->categories;
        $alreadyLinkedCategoryIds = array ();

        foreach ($alreadyLinkedCategories as $alreadyLinkedCategory) {
            $alreadyLinkedCategoryIds [] = $alreadyLinkedCategory->id;
        }

        foreach ($categoriesCollection as $category) {
            if (in_array($category ['categoryId'], $alreadyLinkedCategoryIds)) {
                $user->categories()->detach($category ['categoryId']);
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

        return $user;
    }
}

