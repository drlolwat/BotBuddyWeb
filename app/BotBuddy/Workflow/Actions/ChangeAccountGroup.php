<?php

namespace App\BotBuddy\Workflow\Actions;

use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ChangeAccountGroup extends Action
{
    public function __construct(Workflow $workflow, public SocketService $socket)
    {
        parent::__construct($workflow);
    }

    /**
     * @param Account $model
     * @param array<string, mixed> $data
     */
    public function run(Model $model, array $data): void
    {
        /** @var int $accountGroupId */
        $accountGroupId = $data['account_group_id'];
        $accountGroup = $model->user->account_groups()->find($accountGroupId);
        if (!$accountGroup) {
            $model->user->notifications()->create([
                'message' => "Could not change to account group ID {$accountGroupId} for {$model->email} (was it deleted?)",
                'type' => 'error'
            ]);
            return;
        }

        $model->account_group_id = $accountGroupId;
        $model->save();
    }

    public static function rules(): array
    {
        return [
            'account_group_id' => [
                'required',
                'integer',
                Rule::exists('account_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
        ];
    }
}
