<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use BaoPham\DynamoDb\DynamoDbModel;

class LiveChatMessageLog extends DynamoDbModel
{
    use HasFactory;

    protected $table = 'roca-app-dynamodb-livechat-message-log';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kind',
        'etag',
        'id',

        'snippet', // => [
        //     'type',
        //     'liveChatId',
        //     'authorChannelId',
        //     'publishedAt',
        //     'hasDisplayContent',
        //     'displayMessage',
        //     'textMessageDetails' => [
        //         'messageText'
        //     ]
        // ],

        'authorDetails', // => [
        //     'channelId',
        //     'channelUrl',
        //     'displayName',
        //     'profileImageUrl',
        //     'isVerified',
        //     'isChatOwner',
        //     'isChatSponsor',
        //     'isChatModerator'
        // ],

        'roca', // => [
        //     'text',
        //     'judgement',
        //     'score' => [
        //         'neutral',
        //         'slander',
        //         'sarcasm',
        //         'sexual',
        //         'spam',
        //         'divulgation'
        //     ]
        // ]
    ];
}
