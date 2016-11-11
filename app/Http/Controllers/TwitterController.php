<?php
/**
 * Created by PhpStorm.
 * User: boris
 * Date: 10/11/16
 * Time: 12:10
 */

namespace App\Http\Controllers;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;


class TwitterController extends Controller
{
    const CONSUMER_KEY = 'BYm5Q31Qy0nMyoE9Akljrhlu0';
    const CONSUMER_SECRET = 'YFJfyTK8yJJ8HapEVxqqisaPUDk80A4j2UxvGOPTz7pAXWTP41';
    const ACCESS_TOKEN = '1087231993-MCaLDjk5mvCxNBE145xstgKADOkINjTKLrVynLV';
    const ACCESS_TOKEN_SECRET= 'cjHpMSBJCeeRbT4UCgtjSsu7IO0mx8EHAEfa5aMsbZlNL';

    public function getTweets()
    {
        $connection = new TwitterOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET, self::ACCESS_TOKEN, self::ACCESS_TOKEN_SECRET);

        $tweets = $connection->get("search/tweets",
            [
                "q" => "#tapingo",
                'count' => 5,
            ]);

        $parsedTweets = $this->parseTweets($tweets);

        $pageMax[1] = $parsedTweets['highestId'];
        session(['pages' => $pageMax]);

        return view('tapingoTweetsView')->with([
            'twitterItems' => $parsedTweets['tweets'],
            'nextPage' => 2,
            'prevPage' => 0,

            'nextPageMax' => $parsedTweets['lowestId'],
            'nextPreviousPageMax' => $parsedTweets['highestId'],
            'previousPreviousPageMax' => 0,
            'previousPageMax' => 0,
        ]);
    }

    public function changePage(Request $request)
    {
        if ($request['page'] < 2){
            return redirect()->route('main');
        }

        $connection = new TwitterOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET, self::ACCESS_TOKEN, self::ACCESS_TOKEN_SECRET);
        $tweets = $connection->get("search/tweets",
            [
                "q" => "#tapingo",
                'count' => 5,
                'max_id' => $request['currPageMaxId'] -1
            ]);

        $parsedTweets = $this->parseTweets($tweets);
        $value = session('pages');
        $value[$request['page']] = $parsedTweets['highestId'];
        session(['pages' => $value]);

        $prevPrevMax = 0;
        if ($request['page'] - 2 > 0){
            $prevPrevMax = session('pages')[$request['page'] - 2];
        }

        $prevMax = session('pages')[$request['page'] - 1];


        return view('tapingoTweetsView')->with([
            'twitterItems' => $parsedTweets['tweets'],
            'nextPage' => $request['page'] + 1,
            'prevPage' => $request['page']- 1,

            'nextPageMax' => $parsedTweets['lowestId'],
            'nextPreviousPageMax' => $parsedTweets['highestId'],
            'previousPreviousPageMax' => $prevPrevMax,
            'previousPageMax' => $prevMax +1,
        ]);
    }



    private function parseTweets($tweets)
    {
        $currentMinId = $tweets->statuses[0]->id;
        $currentMaxId = $tweets->statuses[0]->id;

        $parsedTweets = [];
        foreach ($tweets->statuses as $tweet){
            $data['userName'] = $tweet->user->name;
            $data['userNickname'] = "@" . $tweet->user->screen_name;
            $data['userImage'] = $tweet->user->profile_image_url;
            $data['text'] = $tweet->text;
            $createAt = new \DateTime($tweet->created_at);
            $data['createAt'] = $createAt->format("M j, Y H:i");
            $data['id'] = $tweet->id;

            array_push($parsedTweets, $data);

            if ($tweet->id < $currentMinId){
                $currentMinId = $tweet->id;
            }
        }

        return [
            'tweets' => $parsedTweets,
            'lowestId' => $currentMinId,
            'highestId' => $currentMaxId
        ];
    }

}