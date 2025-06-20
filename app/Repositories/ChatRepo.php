<?php

namespace App\Repositories;

use App\Interfaces\IChatRepo;
use App\Models\Chat;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ChatRepo extends BaseRepository implements IChatRepo
{
	public function __construct(Chat $model)
	{
		parent::__construct($model);
	}

	public function getMyChatOrCreateNew()
	{
		$user = Auth::user();

		if ($user) {
			return $this->model->firstOrCreate([
				'client_id' => $user->id,
			], [
				'client_id' => $user->id,
			]);
		}

		return $this->model->firstOrCreate([
			'client_ip' => request()->ip(),
		], [
			'client_ip' => request()->ip(),
		]);
	}

	public function getMyChat()
	{
		$user = Auth::user();

		if ($user) {
			return $this->model->where([
				'client_id' => $user->id,
			])->first();
		}

		return $this->model->where([
			'client_ip' => request()->ip(),
		])->first();
	}
}
