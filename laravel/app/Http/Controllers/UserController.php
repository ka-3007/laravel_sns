<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $name)
    {
        $user = User::where('name', $name)->first()->load(['articles.user', 'articles.likes', 'articles.tags']);

        $articles = $user->articles->sortByDesc('created_at');

        return view('users.show', compact('user', 'articles'));
    }

    public function likes(string $name)
    {
        $user = User::where('name', $name)->first()->load(['likes.user', 'likes.likes', 'likes.tags']);

        $articles = $user->likes->sortByDesc('created_at');

        return view('users.likes', compact('user', 'articles'));
    }

    public function followers(string $name)
    {
        $user = User::where('name', $name)->first()->load('followers.followers');

        $followers = $user->followers->sortByDesc('created_at');

        return view('users.followers', compact('user', 'followers'));
    }

    public function followings(string $name)
    {
        $user = User::where('name', $name)->first()->load('followings.followers');

        $followings = $user->followings->sortByDesc('created_at');

        return view('users.followings', compact('user', 'followings'));
    }

    public function follow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        return compact('name');
    }

    public function unfollow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);

        return compact('name');
    }
}
