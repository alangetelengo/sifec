<div class="table-responsive">
    <table class="table table-bordered table-striped verticle-middle table-responsive-sm" style="min-width: 845px">
        <thead class="thead-success">
            <tr>
                <th>N° acte</th>
                <th>Enfant</th>
                <th>Père</th>
                <th>Mère</th>
                <th>Acte retiré par</th>
                <th>Date du retrait</th>
            </tr>
        </thead>
        <tbody>


                <tr>
                    <td>{{ $acte->niupp }}</td>
                    <td>{{ $acte->declaration->enfant->nom }} <span style="text-transform: capitalize">{{ $acte->declaration->enfant->prenom }}</span> </td>
                    <td>{{ $acte->declaration->pere->nom }} <span style="text-transform: capitalize">{{ $acte->declaration->pere->prenom }}</span> </td>
                    <td>{{ $acte->declaration->mere->nom }} <span style="text-transform: capitalize">{{ $acte->declaration->mere->prenom }}</span> </td>
                    <td>{{ $acte->retrait->retirer_par }} [TELEPHONE : <span style="color: red">{{ $acte->retrait->telephone }}</span>]</td>
                    <td>{{ date("d-m-Y", strtotime($acte->retrait->created_at)) }}</td>
                </tr>

        </tbody>
        {{-- <tfoot>
            <tr>
                <th>N°</th>
                <th>Enfant</th>
                <th>Père</th>
                <th>Mère</th>
                <th>Acte retiré par</th>
                <th>Date du retrait</th>
            </tr>
        </tfoot> --}}
    </table>
</div>
