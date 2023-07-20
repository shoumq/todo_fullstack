<?php

namespace App\Http\Controllers;

use App\Models\TODOList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


/**
 * @OA\Info(
 *     version="1.0",
 *     title="To Do List"
 * )
 */
class APIController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api",
     *     summary="Вывод дел",
     *     tags={"Item"},
     *     @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=false,
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema (
     *          type="array",
     *               @OA\Items(
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="user_id", type="number", example="1"),
     *                 @OA\Property(property="title", type="string", example="Помыть посуду"),
     *                 @OA\Property(property="status", type="number", example="1"),
     *                 @OA\Property(property="created_at", type="time", example="2023-07-06T08:27:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="time", example="2023-07-06T09:45:07.000000Z"),
     *            ),
     *          )
     *         )
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     )
     * )
     */
    public function index(Request $request)
    {
        $list = TODOList::where('user_id', (string)$request->user_id)->latest()->get();
        if (TODOList::where('user_id', (string)$request->user_id)->first() == null) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
        return response()->json($list);
    }


    /**
     * @OA\Delete(
     *     path="/api/delete",
     *     summary="Удалить дело",
     *     tags={"Item"},
     *     @OA\Parameter(
     *      name="item_id",
     *      in="query",
     *      required=false,
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     )
     * )
     */
    public function delete(Request $request)
    {
        $list = TODOList::find($request->item_id);
        if ($list == null) {
            return response()->json([
                'message' => 'Item not found.'
            ], 404);
        } else {
            $list->delete();
            return response()->json([
                'message' => 'Item removed successfully.'
            ]);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/add",
     *     summary="Добавить дело",
     *     tags={"Item"},
     *     @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *    ),
     *     @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=false,
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema (
     *          type="array",
     *               @OA\Items(
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="user_id", type="string", example="1"),
     *                 @OA\Property(property="title", type="string", example="Помыть посуду"),
     *                 @OA\Property(property="status", type="string", example="0"),
     *                 @OA\Property(property="created_at", type="time", example="2023-07-06T08:27:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="time", example="2023-07-06T09:45:07.000000Z"),
     *            ),
     *          )
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     )
     * )
     */
    public function add(Request $request)
    {
        $list = new TODOList();
        $list->user_id = $request->user_id;
        $list->title = $request->title;
        $list->save();

        return response()->json($list);
    }


    /**
     * @OA\Post(
     *     path="/api/save",
     *     summary="Изменить дело",
     *     tags={"Item"},
     *     @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=false,
     *    ),
     *     @OA\Parameter(
     *      name="item_id",
     *      in="query",
     *      required=true,
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema (
     *          type="array",
     *               @OA\Items(
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="user_id", type="string", example="1"),
     *                 @OA\Property(property="title", type="string", example="Помыть посуду"),
     *                 @OA\Property(property="status", type="string", example="0"),
     *                 @OA\Property(property="created_at", type="time", example="2023-07-06T08:27:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="time", example="2023-07-06T09:45:07.000000Z"),
     *            ),
     *          )
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found",
     *     )
     * )
     */
    public function save(Request $request)
    {
        $list = TODOList::where('id', $request->item_id)->first();

        if ($list == null) {
            return response()->json([
                'message' => 'Item not found.'
            ], 404);
        }

        $list->title = $request->title;
        $list->save();

        return $list;
    }


    /**
     * @OA\Post(
     *     path="/api/status",
     *     summary="Изменить статус",
     *     tags={"Item"},
     *     @OA\Parameter(
     *      name="item_id",
     *      in="query",
     *      required=true,
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema (
     *          type="array",
     *               @OA\Items(
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="user_id", type="string", example="1"),
     *                 @OA\Property(property="title", type="string", example="Помыть посуду"),
     *                 @OA\Property(property="status", type="string", example="0"),
     *                 @OA\Property(property="created_at", type="time", example="2023-07-06T08:27:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="time", example="2023-07-06T09:45:07.000000Z"),
     *            ),
     *          )
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Item not found",
     *     )
     * )
     */
    public function status(Request $request)
    {
        $list = TODOList::where('id', $request->item_id)->first();

        if ($list == null) {
            return response()->json([
                'message' => 'Item not found.'
            ], 404);
        }

        if ($list->status == "1") {
            $list->status = "0";
        } else {
            $list->status = "1";
        }
        $list->save();

        return $list;
    }
}
