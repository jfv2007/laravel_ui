<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
 /*  public function store(Request $request)//
    {
        $book = $request->all();
        $book['uuid'] = (string) Str::uuid();


        if($request->hasFile('book_image')){ // si la solicitud tiene un archivo con el nombre
            //* 01 es la imagen lo que graba
            //*/
           // $book['book_image'] = $request->file('book_image')->store('books');
            //tabla=en el formulario ->nombre de la tabla
            ///**

            //* 02 forma para grabar
            //*/
            //la variable $book['book_image'] es de nombre de la base datos
            //$book['book_image'] = $request->file('book_image')->getClientOriginalName();
            //$request->file('book_image')->storeAs('folder_books', $book['book_image']);
            //lo va a guardar en archivo en la  carpeta folder_books y el nombre original
            // aqui va sobre escribir el archivo
            ///**

            //* 03 forma para grabar
            //*/
            //la variable $book['book_image'] va a requerir el tiempo y le va agregar el nombre original
 //           $book['book_image'] = time() . '_' . $request->file('book_image')->getClientOriginalName();
 //           $request->file('book_image')->storeAs('folder_books', $book['book_image']);
            //lo guarda en la carpeta folder y nombre del archivo.

            //* 04 forma para grabar
            //*/
            //la variable $book['book_image'] va a requerir el tiempo y le va agregar el nombre original
       /*     $book['book_image'] = time() . '_' . $request->file('book_image')->getClientOriginalName();
            $request->file('book_image')
                    ->storeAs('book_folder/' . auth()->id(), $book['book_image']);
                //lo va agrabar con el id del usuario    */

   /*     }

       //  aqui agrega solamente el uuid
        Book::create($book);
        return redirect()->route('books.index');
       //
    }
    */

    /**
     * Store option number 5 crea un subfolder y despues crea una carpeta con el id de la imagen*/
    /*  tambien pone el id    */
   public function store(Request $request)
    {
        $book = Book::create([   //llamamos los indentificadores
            'uuid' => (string) Str::orderedUuid(), // registro lo llena automatico
            'title' => $request->title,
        ]);
        if($request->hasFile('book_image'))
        {
            $image = $request->file('book_image')->getClientOriginalName(); //guarda con el nombre original del archivo
            $request->file('book_image')
                    ->storeAs('subfolder/' . $book->id, $image);
            $book->update(['book_image' => $image]);
        }
        return redirect()->route('books.index');
    }


    public function download($uuid)
    {
        $book = Book::where('uuid','=' ,$uuid)->firstOrFail();
        $user=auth()->id();
        $pathToFile = storage_path("app/public/folder_books/" . $book->book_image);
    // $pathToFile = storage_path("app/public/subfolder/$book->id/" . $book->book_image); es un subfolder y la id imagen
    // $pathToFile = storage_path("app/public/subfolder/$user/" . $book->book_image); es un subfolder y la id de usuario


        // return response()->download($pathToFile);
        return response()->file($pathToFile);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $book->update($request->only(['uuid', 'title']));
        if($request->hasFile('book_image'))
        {
            $image = $request->file('book_image')->getClientOriginalName();
            $request->file('book_image')
                ->storeAs('folder_books/' . $book->id, $image);
            if($book->book_image != '')
            {
                unlink(storage_path('app/public/subfolder/' . $book->id . '/' . $book->book_image));
            }
            $book->update(['book_image' => $image]);
        }
        return redirect()->route('books.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
