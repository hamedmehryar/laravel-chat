<?php namespace Hamedmehryar\Chat\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Message extends Eloquent
{

    const MESSAGE_TYPE_TEXT = 0;
    const MESSAGE_TYPE_EVENT_ADD = 1;
    const MESSAGE_TYPE_EVENT_LEAVE = 2;
    const MESSAGE_TYPE_FILE = 3;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['thread'];

    /**
     * The attributes that can be set with Mass Assignment.
     *
     * @var array
     */
    protected $fillable = ['thread_id', 'user_id', 'body','type','mime','extension','file_name'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'body' => 'required',
    ];

    /**
     * Thread relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo('Hamedmehryar\Chat\Models\Thread');
    }

    /**
     * User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Config::get('chat.user_model'));
    }

    /**
     * Participants relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants()
    {
        return $this->hasMany('Hamedmehryar\Chat\Models\Participant', 'thread_id', 'thread_id');
    }

    /**
     * Recipients of this message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients()
    {
        return $this->participants()->where('user_id', '!=', $this->user_id);
    }

    /**
     * mutator for message body that replaces smiley codes with smiley images
     *
     * @param $value
     *
     * @return string
     */
    public function getBodyAttribute($value){
        $value = str_replace(':)', '<div class="smiley smile"></div>', $value);
        $value = str_replace(':-)', '<div class="smiley smile"></div>', $value);
        $value = str_replace('(angry)', '<div class="smiley angry"></div>', $value);
        $value = str_replace(':(', '<div class="smiley sad"></div>', $value);
        $value = str_replace(':-(', '<div class="smiley sad"></div>', $value);
        $value = str_replace(':o', '<div class="smiley surprised"></div>', $value);
        $value = str_replace(':-o', '<div class="smiley surprised"></div>', $value);
        $value = str_replace(':s', '<div class="smiley confused"></div>', $value);
        $value = str_replace(':-s', '<div class="smiley confused"></div>', $value);
        $value = str_replace(':D', '<div class="smiley laugh"></div>', $value);
        $value = str_replace(':-D', '<div class="smiley laugh"></div>', $value);
        $value = str_replace(';)', '<div class="smiley wink"></div>', $value);
        $value = str_replace(';-)', '<div class="smiley wink"></div>', $value);
        $value = str_replace(':|', '<div class="smiley speechless"></div>', $value);
        $value = str_replace(':-|', '<div class="smiley speechless"></div>', $value);
        return $value;

    }
}
