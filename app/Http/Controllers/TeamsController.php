<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTeamRequest;
use App\Models\Teams;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\EditTeamRequest;
use App\Http\Requests\SearchTeamRequest;
use App\Repositories\Teams\TeamsRepository;
use App\Repositories\Teams\TeamsRepositoryInterface;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller
{
    /**
     * @var TeamsRepositoryInterface|TeamsRepository
     */
    protected $teamsRepo;

    public function __construct(TeamsRepositoryInterface $teamsRepo)
    {
        $this->teamsRepo = $teamsRepo;
    }

    //------------------------------------------------------------VIEWS-------------------------------------------------
    public function searchTeam()
    {
        $name = "";
        $teams = $this->teamsRepo->findByName($name);

        return view('teams/searchTeam')->with('teams', $teams);
    }

    public function createTeam()
    {
        return view('teams/createTeam');
    }

    public function createTeamConfirm(CreateTeamRequest $request)
    {
        $name = $request->get('name');

        $request->flash();

        return view('teams/createTeamConfirm')->with('name', $name);
    }

    public function editTeam(int $id)
    {
        $find = $this->teamsRepo->find($id);
        $target = $find->toArray();

        if(empty($target['0'])){
            Session::flash('message', config('global.TARGET_NOT_FOUND'));
            return view(route('team.searchTeam'));
        }
        return view('teams/editTeam')->with('target', $target['0']);
    }

    public function editTeamConfirm(EditTeamRequest $request)
    {
        $data = $request->all();
        $request->flash();
        if(empty($data)){
            Session::flash('message', config('global.TARGET_NOT_FOUND'));
            return view(route('team.searchTeam'));
        }
        return view('teams/editTeamConfirm')->with('data', $data);
    }

    //-------------------------------------------------------------CRUD-------------------------------------------------

    /**
     * Create function
     * Get data from request -> add created time, id -> perform create on DB
     *          1, if email exist -> save old input data -> redirect with error
     *          2, if not -> check if there is a new Team created
     *              2.1, if not -> redirect with error message
     *              2.2, return to search team with success message
     * @param CreateTeamRequest $request data from input
     * @return Application|Factory|View
     */
    public function store(CreateTeamRequest $request)
    {
        $data = $request->all();
        $request->flash();

        //1
        try {
            $this->teamsRepo->create($data);
        } catch (Exception $e) {
           handleExceptionMessage($e);
           return redirect(route('team.createTeam'));
        }

        //2
        if (!$this->teamsRepo->isExist($data['name'])) {
            Session::flash('message', config('messages.CREATE_FAILED'));
            return redirect(route('team.createTeam'));
        }

        Session::flash('message', config('messages.CREATE_SUCCESS'));
        writeLog('Create Team '.$data['name']);
        return $this->index($request);
    }

    /**
     * take request form (id, name) -> update()
     *          -> if result = false means nothing updated -> redirect to edit page with message and old input
     *          -> else means updated! -> redirect to search page with message
     * @param EditTeamRequest $request
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(EditTeamRequest $request)
    {
        $data = $request->all();
        $request->flash();

        try {
            $result = $this->teamsRepo->update($data, $data['id']);
        } catch (QueryException $e) {
            handleExceptionMessage($e);
            return redirect(route('team.editTeam', ['id' => $data['id']]));
        }

        if (!$result) {
            Session::flash('message', config('messages.UPDATE_FAILED'));
            return redirect(route('team.editTeam', ['id' => $data['id']]));
        }

        Session::flash('message', config('messages.UPDATE_SUCCESS'));
        writeLog('Update Team at ID '.$data['id']);
        return $this->index($request);
    }

    /**
     * Search(Read) function
     * @return Application|Factory|View
     * basically an array of result from TEAMS table
     */
    public function index(Request $request, $column='id', $direction='asc')
    {
        $name = $request->get('name');
        $teams = $this->teamsRepo->findByName($name, $column, $direction);

        $request->flash();
        return view('teams.searchTeam', ['teams' => $teams, 'column' => $column, 'direction' => $direction]);
    }

    /**
     * Delete function by ID
     * @param $id
     * @return Application|Factory|View
     */
    public function destroy($id)
    {
        try{
            $result = $this->teamsRepo->delete($id);
        } catch (Exception $e) {
            handleExceptionMessage($e);
            return redirect(route('team.searchTeam'));
        }

        if (!$result) {
            Session::flash('message', config('messages.DELETE_FAILED'));
            return redirect(route('team.searchTeam'));
        }

        writeLog('Delete Team at ID '.$id);
        Session::flash('message', config('messages.DELETE_SUCCESS'));
        return $this->searchTeam();
    }
}
