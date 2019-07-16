<?php

namespace Muserpol\Exports\PlanillaGeneral;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use DB;

class EcoComTramitesLimpiosSheet implements FromQuery,WithTitle, WithHeadings, ShouldAutoSize
{
    protected $eco_com_procedure_id;
    public function __construct($eco_com_procedure_id)
    {
        $this->eco_com_procedure_id = $eco_com_procedure_id;
    }
    public function query()
    {
        $columns = '';
        return EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
            ->info()
            ->beneficiary()
            ->affiliateInfo()
            ->wfstates()
            ->where('economic_complements.wf_current_state_id', 3)
            ->where('economic_complements.eco_com_state_id', 16)
            ->where('economic_complements.total', '>', 0)
            ->whereNotIn('economic_complements.id', function ($query) {
                $query->select('observables.observable_id')
                    ->from('observables')
                    ->where('observables.observable_type', 'economic_complements')
                    ->whereIn('observables.observation_type_id', [1, 2, 6, 8, 9, 13, 20, 21, 22, 24, 25, 26, 31, 36])
                    ->whereNull('observables.deleted_at');
            })
            ->select(DB::raw(EconomicComplement::basic_info_colums() . $columns));
    }
    public function title(): string
    {
        return 'Tramites limpios';
    }
    public function headings(): array
    {
        $new_columns = [];
        $default = [
            'NRO',
            'NUP',
            'Nro Tramite',
            "fecha_de_recepcion",
            'CI Beneficiario',
            'CI Exp BEN',
            'CI COMPLETO BEN',
            "Primer Nombre Beneficiario",
            "Segundo Nombre Beneficiario",
            "Paterno Beneficiario",
            "Materno Beneficiario",
            "Apellido casda Beneficiario",
            "Fecha Nacimiento Beneficiario",
            "Telefonos Beneficiario",
            "celulares Beneficiario",
            "oficialia Beneficiario",
            "libro Beneficiario",
            "partida Beneficiario",
            "fecha_matrimonio Beneficiario",
            "ci_causa",
            "exp_causa",
            "ci_completo_causa",
            "primer_nombre_causahabiente",
            "segundo_nombre_causahabiente",
            "ap_paterno_causahabiente",
            "ap_materno_causahabiente",
            "ape_casada_causahabiente",
            "fecha_nacimiento",
            "codigo_nua_cua",
            "Regional",
            "Tipo de Prestacion",
            "Tipo de Recepcion",
            "Categoria",
            "Grado",
            "Ente Gestor",
            "total_ganado_renta_pensión_SENASIR",
            "reintegro_SENASIR",
            "renta_dignidad_SENASIR",
            "fraccion_saldo_acumulada_APS",
            "fraccion_compensacion_cotizaciones_APS",
            "fraccion_solidaria_vejez_APS",
            "total_renta",
            "total_renta_neto",
            "antiguedad",
            "salario_referencial",
            "salario_cotizable",
            "diferencia",
            "total_semestre",
            "factor_complementario",
            "total_complemento",
            "Ubicacion",
            "tipoe_beneficiario",
            "flujo",
        ];
        return array_merge($default, $new_columns);
    }
}
