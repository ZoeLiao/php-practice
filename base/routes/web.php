<?php

use App\Task;
use Illuminate\Http\Request;

Route::group(['middleware' => 'web'], function () {

    /**
     * 顯示所有任務
     */
    Route::get('/', function () {
        $tasks = Task::orderBy('created_at', 'asc')->get();

        return view('tasks', [
            'tasks' => $tasks
        ]);
    });

    /**
     * 增加新的任務
     */
    Route::post('/task', function (Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
		]);

		if ($validator->fails()) {
			return redirect('/')
				->withInput()
				/**
				 * 透過給定的驗證器實例將錯誤訊息快閃至 session 中
				 * 所以我們可以在視圖中透過 $errors 變數存取它們。
				 */
				->withErrors($validator);
		}

        $task = new Task;
        $task->name = $request->name;
        $task->save();

        return redirect('/');
    });

    /**
     * 刪除任務
     */
    Route::delete('/task/{task}', function (Task $task) {
        $task->delete();

        return redirect('/');
    });
});
