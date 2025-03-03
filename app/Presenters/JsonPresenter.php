<?php

declare(strict_types=1);

namespace App\Presenters;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class JsonPresenter
{
    private string $message = '';
    private string $error = '';
    private mixed $data = null;
    private array $meta = [];
    private int $statusCode = 200;

    public static function make(): self
    {
        return new self();
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }

    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setPagination(LengthAwarePaginator $paginator): self
    {
        $this->meta['pagination'] = [
            'total' => $paginator->total(),
            'count' => $paginator->count(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'links' => [
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];
        return $this;
    }

    public function addMeta(string $key, mixed $value): self
    {
        $this->meta[$key] = $value;
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond(): JsonResponse
    {
        $response = [];

        if ($this->message !== '') {
            $response['message'] = $this->message;
        }

        if ($this->error !== '') {
            $response['error'] = $this->error;
        }

        if ($this->data !== null) {
            $response['data'] = $this->data;
        }

        if (!empty($this->meta)) {
            $response['meta'] = $this->meta;
        }

        return response()->json($response, $this->statusCode);
    }
}
