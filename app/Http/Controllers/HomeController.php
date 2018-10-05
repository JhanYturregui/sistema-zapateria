<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Acceso;
use App\Opcion;
use App\Categoria;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('paginas');
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accesoController = new AccesoController();
        $datos = $accesoController->obtenerMenus();
        
        return view('home', ['datos'=>$datos]);
    }
}
