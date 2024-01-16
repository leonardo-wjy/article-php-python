<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        $payload = [];
        $response = curl_request("GET", "article", $payload);

        $dataArticles = [];
        if($response['code'] === 200)
        {
            $dataArticles = json_decode($response["body"])->posts;
        }

        $data = [
            "articles" => $dataArticles
        ];

        return view('home', $data);
    }

    public function add()
    {
        return view('add');
    }

    public function getById($id)
    {
        $payload = [];
        $response = curl_request("GET", "article/$id", $payload);

        $dataArticles = [];
        if($response['code'] === 200)
        {
            $dataArticles = json_decode($response["body"])->post;
        }

        $data = [
            "article" => $dataArticles
        ];

        return view('edit', $data);
    }

    public function save()
    {
        try{
            $rules = [
                "title" => [
                    "rules" => "required"
                ],
                "content" => [
                    "rules" => "required"
                ],
                "category" => [
                    "rules" => "required"
                ],
            ];

            if (!$this->validate($rules)) {
                $errorList = $this->validator->getErrors();
                $data = [
                    "status"    => false,
                    "message"   => $errorList[array_keys($errorList)[0]]
                ];
                echo json_encode($data);
                return;
            }

            if ($this->validate($rules)) {
                $payload = json_encode([
                    "title" => $this->request->getPost("title"),
                    "content" => $this->request->getPost("content"),
                    "category" => $this->request->getPost("category"),
                    "status" => $this->request->getPost("status")
                ]);
                
                $response = curl_request("POST", "article", $payload);

                if ($response["code"] === 201) {
                    $data = [
                        "status"            => true,
                        "message"   => "Data Berhasil Disimpan"
                    ];
                    echo json_encode($data);
                } else {
                    $message = is_object(json_decode($response["body"])) ? json_decode($response["body"])->error : 'Data Gagal Disimpan';
                    $data = [
                        "status"            => false,
                        "message"    => $message
                    ];
                    echo json_encode($data);
                }
            } else {
                $data = [
                    "status"            => false,
                    "message"    => "Data Gagal Disimpan"
                ];
                echo json_encode($data);
            }
        }
        catch(\Exception $e)
        {
            $data = [
                "status"            => false,
                "message"    => $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()
            ];
            echo json_encode($data);
        }
        return;
    }

    public function update()
    {
        try{
            $rules = [
                "title" => [
                    "rules" => "required"
                ],
                "content" => [
                    "rules" => "required"
                ],
                "category" => [
                    "rules" => "required"
                ],
            ];

            if (!$this->validate($rules)) {
                $errorList = $this->validator->getErrors();
                $data = [
                    "status"    => false,
                    "message"   => $errorList[array_keys($errorList)[0]]
                ];
                echo json_encode($data);
                return;
            }

            if ($this->validate($rules)) {
                $id = $this->request->getPost("id");

                $payload = json_encode([
                    "title" => $this->request->getPost("title"),
                    "content" => $this->request->getPost("content"),
                    "category" => $this->request->getPost("category"),
                    "status" => $this->request->getPost("status")
                ]);
                
                $response = curl_request("PATCH", "article/$id", $payload);

                if ($response["code"] === 200) {
                    $data = [
                        "status"            => true,
                        "message"   => "Data Berhasil Disimpan"
                    ];
                    echo json_encode($data);
                } else {
                    $data = [
                        "status"            => false,
                        "message"    => "Data gagal Disimpan",
                        "response" => $response
                    ];
                    echo json_encode($data);
                }
            } else {
                $data = [
                    "status"            => false,
                    "message"    => "Data Gagal Disimpan"
                ];
                echo json_encode($data);
            }
        }
        catch(\Exception $e)
        {
            $data = [
                "status"            => false,
                "message"    => $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()
            ];
            echo json_encode($data);
        }
        return;
    }

    public function trash()
    {
        try{
            $id = $this->request->getPost("id");

            $payload = json_encode([
                "status" => "Trash"
            ]);
            
            $response = curl_request("PATCH", "article/$id", $payload);

            if ($response["code"] === 200) {
                $data = [
                    "status"            => true,
                    "message"   => "Data Berhasil Disimpan"
                ];
                echo json_encode($data);
            } else {
                $message = is_object(json_decode($response["body"])) ? json_decode($response["body"])->error : 'Data Gagal Disimpan';
                $data = [
                    "status"            => false,
                    "message"    => $message
                ];
                echo json_encode($data);
            }
        }
        catch(\Exception $e)
        {
            $data = [
                "status"            => false,
                "message"    => $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()
            ];
            echo json_encode($data);
        }
        return;
    }
}