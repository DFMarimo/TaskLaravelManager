<?php

namespace App\Repositories;

use App\Models\Expertise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function all()
    {
        return User::all();
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password']
            ]);

            /*
             * set expertise and main expertise in DB
             *
             *  expertise_id  |  user_id   |  is_main_expertise |
             *  ------------- | ---------- | ------------------ |
             *      1         |     1      |       false        |
             *      2         |     1      |       true         |
             *      3         |     1      |       false        |
             *      4         |     1      |       false        |
             *
             * */
            foreach ($data['expertises'] as $expertiseId) {
                if ($data['is_main_expertise'] == $expertiseId) {
                    $user->expertises()->attach($expertiseId, ['is_main_expertise' => true]);
                } else {
                    $user->expertises()->attach($expertiseId, ['is_main_expertise' => false]);
                }
            }

            /* counter how many user in expertise */
            foreach ($data['expertises'] as $expertiseId) {
                ExpertiseRepository::userCounter($expertiseId);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function update($data, User $user)
    {
        $user = $user->query()->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ]);

        foreach ($user->expertises()->pluck('id') as $expertiseId) {
            ExpertiseRepository::userCounter($expertiseId, true);
        }
        $user->expertises()->detach();


        /*
         * set expertise and main expertise in DB
         */
        foreach ($data['expertises'] as $expertiseId) {
            if ($data['is_main_expertise'] == $expertiseId) {
                $user->expertises()->attach($expertiseId, ['is_main_expertise' => true]);
            } else {
                $user->expertises()->attach($expertiseId, ['is_main_expertise' => false]);
            }
        }

        foreach ($data['expertises'] as $expertiseId) {
            ExpertiseRepository::userCounter($expertiseId);
        }

        return $user;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }


    static public function minScore(User $user, $point = 10)
    {
        $user->increment('score', $point);
        return 'score added.';
    }

    static public function addScore(User $user, $point = 10)
    {
        $user->decrement('score', $point);
        return 'score denied.';
    }

}
