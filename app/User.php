<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'username', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function createdMessages() {
		return $this->hasMany('App\Message');
	}

	public function receivedMessages() {
		return $this->hasMany('App\Location');
	}

	public function getCurrentMessages() {
		// Get the locations without duplicates
		$locations = Location::query()->where('user_id', '=', $this->id)->groupBy('message_id')->get();

		// Check foreach message if it's the owner
		$new_locations = $locations->filter( function( $location )  {
			if( $location->message->owner()->id == $this->id )
				return true;
		});

		$messages = $new_locations->lists('message');

		return $messages;

	}

	public function GCMToken() {
		return $this->gcm_token;
	}

	public function setGCMToken( $token ) {
		$this->gcm_token = $token;
	}

	public function scopeRandom($query)
	{
		return $query->orderBy(DB::raw('RAND()'));
	}

	public function scopeRandomExcept($query, $ids) {
		return $query->whereNotIn('id', $ids)->orderBy(DB::raw('RAND()'));
	}

	function JSONFormat() {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'username' => $this->username,
			'email'    => $this->email,
		];
	}


}
