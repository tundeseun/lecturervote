<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // public function createPayment(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'staffName' => 'required|string',
    //         'staffBankAccountNumber' => 'required|string',
    //         'staffBankName' => 'required|string',
    //         'staffPfNumber' => 'required|string',
    //         'amount' => 'required|numeric',
    //         'departmentName' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $response = [
    //         'PaymentRequestId' => uniqid('REQ-'),
    //         'RequestDate' => now()->toDateTimeString(),
    //         'StaffName' => $request->staffName,
    //         'StaffCode' => $request->staffPfNumber,
    //         'BeneficiaryName' => $request->staffName,
    //         'RefNo' => uniqid('REF-'),
    //         'Amount' => $request->amount,
    //         'Description' => $request->input(''),
    //         'SubUnitName' => $request->input('subUnitName', null),
    //         'DepartmentName' => $request->departmentName,
    //         'RequestTypeName' => '',
    //         'AccountPoint' => 'PGS',
    //     ];

    //     return response()->json($response, 201);
    // }








    // protected $payments = []; // Temporary in-memory storage. Replace with a database in production.

    //     public function storePayment(Request $request)
    //     {
    //         $validator = Validator::make($request->all(), [
    //             'staffName' => 'required|string',
    //             'staffBankAccountNumber' => 'required|string',
    //             'staffBankName' => 'required|string',
    //             'staffPfNumber' => 'required|string',
    //             'amount' => 'required|numeric',
    //             'facultyName' => 'required|string',
    //             'departmentName' => 'required|string',
    //             'description' => 'required|string',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 422);
    //         }

    //         $paymentRequestId = uniqid('REQ-');
    //         $refNo = "PGS/PhD/" . now()->format('Ymd') . '/' . sprintf('%06d', random_int(1, 999999));
    //         $requestDate = now()->toDateTimeString();

    //         $paymentData = [
    //             'PaymentRequestId' => $paymentRequestId,
    //             'RequestDate' => $requestDate,
    //             'StaffName' => $request->staffName,
    //             'StaffCode' => $request->staffPfNumber,
    //             'BeneficiaryName' => $request->staffName,
    //             'staffBankAccountNumber' => $request->staffBankAccountNumber,
    //             'staffBankName' => $request->staffBankName,
    //             'RefNo' => $refNo,
    //             'Amount' => $request->amount,
    //             'Description' => $request->description,
    //             'SubUnitName' => $request->input('subUnitName', null),
    //             'DepartmentName' => $request->departmentName,
    //             'RequestTypeName' => 'Payment',
    //             'AccountPoint' => 'PGS',
    //         ];

    //         // Save to the in-memory storage (replace with a database in production)
    //         $this->payments[] = $paymentData;

    //         return response()->json(['message' => 'Payment request stored successfully', 'data' => $paymentData], 201);
    //     }

    //     // public function getPayment()
    //     // {
    //     //     // Retrieve from the in-memory storage (replace with a database in production)
    //     //     return response()->json($this->payments);
    //     // }

    //     public function getPayment($id)
    // {
    //     // Retrieve the payment by ID
    //     $payment = Payment::find($id);

    //     // Check if the payment exists
    //     if (!$payment) {
    //         return response()->json(['error' => 'Payment not found.'], 404);
    //     }

    //     // Return the payment details
    //     return response()->json($payment, 200);
    // }

    private static $payments = [];

    public function storePayment(Request $request)
    {
        $request->validate([
            'staffName' => 'required|string|max:255',
            'staffBankAccountNumber' => 'required|string|max:20',
            'staffBankName' => 'required|string|max:255',
            'staffPfNumber' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'facultyName' => 'required|string|max:255',
            'departmentName' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $paymentRequestId = Str::uuid();

        $requestDate = now();

        $transactionDate = $requestDate->format('Ymd');
        $serialNumber = sprintf('%06d', mt_rand(1, 999999));
        $refNo = "PGS/PhD/$transactionDate/$serialNumber";

        $paymentData = [
            'PaymentRequestId' => $paymentRequestId,
            'RequestDate' => $requestDate,
            'StaffName' => $request->staffName,
            'StaffCode' => $request->staffPfNumber,
            'BeneficiaryName' => $request->staffName,
            'staffBankAccountNumber' => $request->staffBankAccountNumber,
            'staffBankName' => $request->staffBankName,
            'RefNo' => $refNo,
            'Amount' => $request->amount,
            'Description' => $request->description,
            'SubUnitName' => $request->input('subUnitName', null),
            'DepartmentName' => $request->departmentName,
            'RequestTypeName' => $request->input('requestTypeName', 'Salary Payment'),
            'AccountPoint' => 'PGS',
        ];

        // Payment::create($paymentData);

        session()->put("payment.$paymentRequestId", $paymentData);
        return response()->json([
            'message' => 'Payment request successfully sent to ADMON.',
            'data' => $paymentData
        ], 201);
    }

    public function getPayment($id)
    {
        $payment = collect(self::$payments)->firstWhere('PaymentRequestId', $id);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found.'], 404);
        }

        return response()->json($payment, 200);
    }

    public function getAllPayments()
    {
        return response()->json(self::$payments, 200);
    }
}
