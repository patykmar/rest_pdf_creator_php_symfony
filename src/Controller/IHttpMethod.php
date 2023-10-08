<?php

namespace App\Controller;

interface IHttpMethod
{
    const GET = 'GET';
    const OPTIONS = 'OPTIONS';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';
}
