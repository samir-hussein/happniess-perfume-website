<?php

namespace App\Repositories;

use App\Interfaces\IChatRepo;
use App\Models\Chat;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\GeneralException;

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

		if (!request()->cookie('chat_id')) {
			throw new GeneralException('Chat ID not found');
		}

		return $this->model->firstOrCreate([
			'client_ip' => request()->cookie('chat_id'),
		], [
			'client_ip' => request()->cookie('chat_id'),
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
			'client_ip' => request()->cookie('chat_id'),
		])->first();
	}
}
