<?php

class FinalResult
{
    function results($f)
    {
        $d = fopen($f, "r");
        $h = fgetcsv($d);
        $rcs = [];
        while (!feof($d)) {
            // $r = fgetcsv($d);
            if (count($r) == 16) {
                if (empty($r[8]) || $r[8] == "0") {
                    $amt = 0;
                } else {
                    $amt = (float) $r[8];
                }
                if (empty($r[6]) || $r[6] == "0") {
                    $ban = "Bank account number missing";
                } else {
                    $ban = (int) $r[6];
                }
                if (empty($r[2])) {
                    $bac = "Bank branch code missing";
                } else {
                    $bac = $r[2];
                }
                if (empty($r[10]) && empty($r[11])) {
                    $e2e = "End to end id missing";
                } else {
                    $e2e = $r[10] . $r[11];
                }
                $rcd = [
                    "amount" => [
                        "currency" => $h[0],
                        "subunits" => (int) ($amt * 100)
                    ],
                    "bank_account_name" => str_replace(" ", "_", strtolower($r[7])),
                    "bank_account_number" => $ban,
                    "bank_branch_code" => $bac,
                    "bank_code" => $r[0],
                    "end_to_end_id" => $e2e,
                ];
                $rcs[] = $rcd;
            }
        }
        $rcs = array_filter($rcs);
        return [
            "filename" => basename($f),
            "document" => $d,
            "failure_code" => $h[1],
            "failure_message" => $h[2],
            "records" => $rcs
        ];
    }
}
