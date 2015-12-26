<?php namespace Hamedmehryar\Chat\Traits;

use Hamedmehryar\Chat\Models\Message;
use Hamedmehryar\Chat\Models\Thread;
use Hamedmehryar\Chat\Models\Participant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

trait Chatable
{
    /**
     * Message relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('Hamedmehryar\Chat\Models\Message');
    }

    /**
     * Thread relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function threads()
    {
        return $this->belongsToMany('Hamedmehryar\Chat\Models\Thread', 'participants');
    }

    /**
     * Returns the new messages count for user
     *
     * @return int
     */
    public function newMessagesCount()
    {
        return count($this->threadsWithNewMessages());
    }

    /**
     * Returns all threads with new messages
     *
     * @return array
     */
    public function threadsWithNewMessages()
    {
        $threadsWithNewMessages = [];
        $participants = Participant::where('user_id', $this->id)->lists('last_read', 'thread_id');

        /**
         * @todo: see if we can fix this more in the future.
         * Illuminate\Foundation is not available through composer, only in laravel/framework which
         * I don't want to include as a dependency for this package...it's overkill. So let's
         * exclude this check in the testing environment.
         */
        if (getenv('APP_ENV') == 'testing' || !str_contains(\Illuminate\Foundation\Application::VERSION, '5.0')) {
            $participants = $participants->all();
        }

        if ($participants) {
            $threads = Thread::whereIn('id', array_keys($participants))->get();

            foreach ($threads as $thread) {
                if ($thread->updated_at > $participants[$thread->id]) {
                    $threadsWithNewMessages[] = $thread->id;
                }
            }
        }

        return $threadsWithNewMessages;
    }


    public function newMessagesCountForPoll()
    {
        $newMessagesCount = 0;
        foreach($this->threadsWithNewMessagesForPoll() as $thread){
            $allMessagesCount = $thread->messages()->where('user_id', '!=', $this->id)->where('created_at', '>', $thread->getParticipantFromUser($this->id)->last_poll)->orderBy('id', 'desc')->limit(100)->count();
            $newMessagesCount += $allMessagesCount;
        }
        return $newMessagesCount;
    }
    public function threadsWithNewMessagesForPoll(){

        $updatedThreadsIds = $this->threadsWithNewMessages();
        $updatedThreads = Thread::whereIn('id', $updatedThreadsIds)->get();
        $updatedThreads = $updatedThreads->filter(function($thread)
        {
            return strtotime($thread->updated_at) >= strtotime($thread->getParticipantFromUser($this->id)->last_poll);
        });

        foreach ($updatedThreads as $thread){
            foreach($thread->messages as $message){
                $message->timeDiffForHumen = $message->created_at->diffForHumans();
                $message->senderDetails = $message->user->first_name." ".$message->user->last_name;
                $message->layout = ($message->user_id==$this->id?"right":"left");
            }
        }
        return $updatedThreads;

    }

    public function pollSuccessForThreads($threads){

        foreach($threads as $thread){
            $participant = $thread->getParticipantFromUser($this->id);
            $participant->last_poll = new Carbon;
            $participant->save();
        }

    }

    public function leaveThread($id){
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return false;
        }
        Thread::find($id)->removeParticipant($this->id);
        return true;
    }
}
