<?php

namespace App\Http\Controllers;

use App\Dashboard\DashboardFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Nessworthy\TextMarketer\Endpoint\AccountEndpoint;
use App\LastCaseReference;

class HomeController extends Controller
{
    /**
     * @var DashboardFactory
     */
    private $dashboardFactory;

    /**
     * Create a new controller instance.
     *
     * @param DashboardFactory $dashboardFactory
     */
    public function __construct(DashboardFactory $dashboardFactory)
    {

        //$this->middleware('auth');

        $this->dashboardFactory = $dashboardFactory;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $slug = $user->userRole->dashboard_title;

        $dashboard = $this->dashboardFactory->create($slug);

        $dashData = $dashboard->getData($user);
        $view = 'dashboards.' . $slug;

        $data = [
            'user' => $user,
            'dashData' => $dashData,
        ];

        return view($view, $data);
    }

    public function update(Request $request)
    {
    }

    public function show(Request $request)
    {
    }
}
