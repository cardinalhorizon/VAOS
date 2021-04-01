<?php


namespace App\Exports;
use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromArray;


class ScheduleExport implements FromArray
{
    private function findPrimary($aircraft_list) {
        $array = [];
        foreach ($aircraft_list as $a) {
            if ($a['pivot']['primary']) {
                return $a->icao;
            }
        }
        return null;
    }
    public function array(): array
    {
        $schedule = Schedule::with('depapt', 'arrapt', 'airline', 'aircraft_group', 'aircraft')->get();
        $out = [];

        foreach ($schedule as $item) {
            array_push($out, [
                'id' => $item->id,
                'airline' => $item->airline->icao,
                'flightnum' => $item->flightnum,
                'callsign' => $item->callsign,
                'depicao' => $item->depapt->icao,
                'arricao' => $item->arrapt->icao,
                'primary_aircraft' => self::findPrimary($item->aircraft_group)
            ]);
        }
        return $out;
    }
}
