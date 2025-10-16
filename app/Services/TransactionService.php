<?php

namespace App\Services;

use App\Libraries\OpenAiLibrary;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TransactionService
{
    public function __construct(
        protected OpenAiLibrary $openAiLibrary
    ) {}

    public function list()
    {
        return Transaction::get();
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            $result = $this->convertDataToResult($data);

            $user = User::where('telegram_id', $data['telegram_id'])->first();

            $category = $this->defineCategory($result);

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'type' => $result['type'],
                'value' => $result['value'],
                'description' => $result['description'],
                'original_message' => $data['original_message']
            ]);

            $transaction->load(['user', 'category']);

            DB::commit();

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e);
        }
    }

    public function delete(int $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
    }

    private function convertDataToResult(array $data)
    {
        $result = $this->openAiLibrary->naturalLanguageToJsonConverter($data['original_message']);
        $required_fields = ['type', 'category', 'value', 'description'];

        foreach ($required_fields as $field) {
            if (!isset($result[$field])) {
                throw new RuntimeException("Não foi possível interpretar a mensagem");
            }
        }

        return $result;
    }

    private function defineCategory(array $result)
    {
        $new_category = strtolower(trim($result['category'] ?? 'desconhecido'));

        $category = Category::where('title', $new_category)->first();

        if ($category?->exists()) {
            return $category;
        }

        return Category::create([
            'title' => $new_category
        ]);
    }
}
