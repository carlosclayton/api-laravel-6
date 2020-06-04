<?php

namespace App\Http\Controllers;

use App\Criteria\OnlyTrashedCriteria;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Repositories\CategoryRepository;
use App\Validators\CategoryValidator;

/**
 * Class CategoriesController.
 *
 * @package namespace App\Http\Controllers;
 */
class CategoriesController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected $repository;

    /**
     * @var CategoryValidator
     */
    protected $validator;

    /**
     * CategoriesController constructor.
     *
     * @param CategoryRepository $repository
     * @param CategoryValidator $validator
     */
    public function __construct(CategoryRepository $repository, CategoryValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @OA\Get(
     *     tags={"Categories"},
     *     path="/api/categories",
     *     summary="List of categories",
     *     description="Return a list of categories",
     *     @OA\Response(response="200", description="An json"),
     *      security={
     *           {"apiKey": {}}
     *       }
     * )
     */
    public function index(Request $request)
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $categories = $this->repository->paginate($request->get('limit', 10), $request->get('page', 1));
        return response()->json([
            'data' => $categories,
        ]);
    }

    /**
     * @OA\Post(
     *      tags={"Categories"},
     *      path="/api/categories",
     *      summary="Store a category",
     *      description="Return message",
     *      @OA\Parameter(
     *          name="name",
     *          description="Name field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          description="Description",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Store categories"),
     *      security={
     *           {"apiKey": {}}
     *      }
     * )
     */
    public function store(CategoryCreateRequest $request)
    {
        try {
            $this->repository->create($request->all());
            return response()->json([
                'message' => 'Category created.',
            ]);
        } catch (ValidatorException $e) {
            return response()->json([
                'message' => $e->getMessageBag()
            ]);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/categories/{id}",
     *      operationId="getCategoryById",
     *      @OA\Parameter(
     *              name ="id",
     *              in = "path",
     *              description = "ID of category to return",
     *              required = true,
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *      ),
     *      summary="Show a category",
     *      description="Return a category",
     *      @OA\Response(response="200", description="An json"),
     *      security={
     *          {"apiKey": {}}
     *      }
     *  )
     */
    public function show($id)
    {
        $category = $this->repository->find($id);
        return response()->json([
            'data' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->repository->find($id);

        return view('categories.edit', compact('category'));
    }

    /**
     * @OA\Put(
     * path="/api/categories/{id}",
     * summary="Update a category",
     * description="Update a category",
     * operationId="getCategoryById",
     * @OA\Parameter(
     * name ="id",
     * in = "path",
     * description = "ID of category to return",
     * required = true,
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Parameter(
     * name="name",
     * description="Name field",
     * required=true,
     * in="query",
     * @OA\Schema(
     * type="string"
     * )*
     * ),
     * @OA\Parameter(
     * name="description",
     * description="Description",
     * required=true,
     * in="query",
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Response(response="200", description="Store categories"),
     * security={
     * {"apiKey": {}}
     * }
     * )
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $this->repository->update($request->all(), $id);
            return response()->json([
                'message' => 'Category updated.',
            ]);
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessageBag()
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json([
            'message' => 'Category deleted.'
        ]);
    }

    public function trashed()
    {
        $this->repository->pushCriteria(new OnlyTrashedCriteria());
        $categories = $this->repository->paginate(10);
        return response()->json([
            'data' => $categories,
        ]);
    }

    public function restore($id)
    {
        try {
            $this->repository->restore($id);
            return response()->json([
                'data' => 'Category restored.'
            ]);
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => true, 'message' => $e->getMessageBag()
            ]);
        }
    }


}
