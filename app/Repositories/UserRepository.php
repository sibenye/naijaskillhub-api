<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository {

    public function model() {
        return 'App\Models\DAO\User';
    }

    public function getUserByEmailAddress($emailAddress) {
        return $this->model->where('emailAddress', $emailAddress)->first();
    }

    public function getUserAttributes($userId, $attributeNames = []) {
        $user = $this->get($userId);

        if (!empty($attributeNames)) {
            $userAttributes = $user->userAttributes->whereIn('name',
                    $attributeNames);
            return $userAttributes;
        } else {
            $userAttributes = $user->userAttributes;
            return $userAttributes;
        }
    }

    public function linkUserToCategory($userId, $categoriesCollection) {
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

    public function unlinkUserFromCategory($userId, $categoriesCollection) {
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

    public function upsertUserAttributeValue(Model $user, $attributesCollection) {
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
                $user->userAttributes()->attach(
                        $userAttributeRequest ['attributeId'],
                        [
                                'attributeValue' => $userAttributeRequest ['attributeValue']
                        ]);
            }
        }
    }

    public function createUser($requestBody) {
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

