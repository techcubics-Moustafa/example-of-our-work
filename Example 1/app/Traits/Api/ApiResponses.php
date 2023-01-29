<?php

namespace App\Traits\Api;

use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponses
{
    protected function success($data = null, $paginator = null, $message = '', $status = true, $code = 200): \Illuminate\Http\JsonResponse
    {
        $object = [
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ];

        if (!is_null($paginator)) $object['paginator'] = $this->paginate($paginator);

        return response()->json($object, $code);
    }

    protected function failure($message, $code = 422, $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => false,
        ], $code);
    }

    protected function exception($message, $code = 500): \Illuminate\Http\JsonResponse
    {
        return $this->failure($message->getMessage(), $code);
    }

    protected function paginate(LengthAwarePaginator $paginator): array
    {
        return [
            'total' => $paginator->total(), // total item return
            'count' => $paginator->count(), // Get the number of items for the current page.
            'currentPage' => $paginator->currentPage(), // Get the current page number.
            'lastPage' => $paginator->lastPage(),
            'hasMorePages' => $paginator->hasMorePages(), // Determine if there is more items in the data store.
            'nextPageUrl' => $paginator->nextPageUrl(), // Get the URL for the next page.
            'previousPageUrl' => $paginator->previousPageUrl()
        ];
    }
}
