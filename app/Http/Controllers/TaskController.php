<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Repositories\TaskRepository;

/**
 * Class TasksController.
 *
 * @package namespace App\Http\Controllers;
 */
class TaskController extends Controller
{
    /**
     * @var TaskRepository
     */
    protected $repository;


    /**
     * TasksController constructor.
     *
     * @param TaskRepository $repository
     * @param TaskValidator $validator
     */
    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = $this->repository->all();
        // dd($tasks);

        if (request()->wantsJson()) {

            return response()->json([
                'details' => $tasks,
            ]);
        }

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TaskCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(TaskCreateRequest $request)
    {
        try {


            $task = $this->repository->create($request->all());

            $response = [
                'message' => 'Task created.',
                'data'    => $task->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  TaskUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(TaskUpdateRequest $request)
    {
        try {


            $task = $this->repository->update($request->all(), $request->id);

            $response = [
                'message' => 'Task updated.',
                'data'    => $task->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            dd($e->getMessageBag());
            if ($request->wantsJson()) {
                dd($e->getMessageBag());
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $deleted = $this->repository->delete($request->id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Task deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Task deleted.');
    }
}
