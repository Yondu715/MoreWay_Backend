<?php

namespace App\Services\Friend;

use App\DTO\Friend\AcceptFriendDto;
use App\DTO\Friend\AddFriendDto;
use App\Enums\Friend\RelationshipTypeId;
use App\Exceptions\Friend\FriendRequestConflict;
use App\Exceptions\Friend\FriendRequestNotFound;
use App\Exceptions\User\UserNotFound;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class FriendService
{
    /**
     * @param int $userId
     * @return Collection<int, User>
     */
    public function getUserFriends(int $userId): Collection
    {
        /** @var ?User */
        $user = User::query()->find($userId);
        if (!$user) {
            throw new UserNotFound();
        }
        return $user->friends()->wherePivot('relationship_id', '=', RelationshipTypeId::FRIEND)->get();
    }

    /**
     * @param int $userId
     * @return Collection<int, Friend>
     */
    public function getFriendRequests(int $userId): Collection
    {
        return Friend::query()->where([
            'friend_id' => $userId,
            'relationship_id' => RelationshipTypeId::REQUEST
        ])->with('user')->get();
    }

    public function deleteFriend(int $userId, int $friendId): void
    {
        Friend::query()->where([
            'user_id' => $userId,
            'friend_id' => $friendId
        ])->orWhere([
            'user_id' => $friendId,
            'friend_id' => $userId
        ])->delete();
    }

    public function addFriendRequest(AddFriendDto $addFriendDto): User
    {
        /** @var ?Friend */
        $request = Friend::query()->firstWhere([
            'user_id' => $addFriendDto->userId,
            'friend_id' => $addFriendDto->friendId
        ]);
        if ($request) {
            throw new FriendRequestConflict();
        }

        /** @var Friend */
        $request = Friend::query()->create([
            'user_id' => $addFriendDto->userId,
            'friend_id' => $addFriendDto->friendId,
            'relationship_id' => RelationshipTypeId::REQUEST
        ]);
        return $request->friend;
    }

    public function acceptFriendRequest(AcceptFriendDto $acceptFriendDto): void
    {
        /** @var ?Friend */
        $request = Friend::query()->find($acceptFriendDto->requestId);
        if (!$request) {
            throw new FriendRequestNotFound();
        }
        $request->update([
            'relationship_id' => RelationshipTypeId::FRIEND
        ]);
        Friend::query()->create([
            'user_id' => $request->friend_id,
            'friend_id' => $request->user_id,
            'relationship_id' => RelationshipTypeId::FRIEND
        ]);
    }

    public function rejectFriendRequest(int $requestId): ?bool
    {
        return Friend::query()->find($requestId)->delete();
    }
}
