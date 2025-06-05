<?php

namespace App\Mail;

use App\Models\DemandePortailParticulier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Modules\Deces\Entities\ActeDeces;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Naissance\Entities\ActeNaissance;

class TransmissionDocumentPortail extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $pieces;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(DemandePortailParticulier $dmd, Array $listePieces)
    {
        $this->demande = $dmd;
        $this->pieces = $listePieces;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

       // $size = count($this->pieces);
      //  for($i=0; $i<$size; $i++){
        $piece = "";
            if($this->demande->type_acte=="Décès"){
                $ad = ActeDeces::find($this->demande->num_acte);
                $numDec =  $ad->code_declaration_deces;
                if($this->demande->type_document == "Copie"){

                    $piece = route('copieActeDeces',$numDec);
                }

                if($this->demande->type_document == "Extrait acte décès"){
                    $piece = route("acteDeces.displayExtrait",$numDec);
                }

             
            }

            if($this->demande->type_acte=="Naissance"){
                $ad = ActeNaissance::find($this->demande->num_acte);
                $numDec =  $ad->code_declaration_naissance;
                if($this->demande->type_document == "Copie"){

                    $piece = route('copieActeNaissance',$numDec);
                }

                if($this->demande->type_document == "Extrait acte naissance"){
                    $piece = route("acteNaissance.displayExtraitActe",$numDec);
                }

               
            }

            if($this->demande->type_acte=="Mariage"){
                $ad = ActeMariage::find($this->demande->num_acte);
                $numDec =  $ad->code_declaration_mariage;
                if($this->demande->type_document == "Copie"){

                    $piece = route('copieActeMariage',$numDec);
                }

                if($this->demande->type_document == "Extrait acte mariage"){
                    $piece = route("extraitActeMariage",$numDec);
                }

               
            }

           // $file = file_get_contents($piece);
            //Storage::disk('public/pieces')->put('piece'.$this->demande->code_demande.'.pdf', $file);
       // }


        return $this->subject("Transmission pièces d'état civil - SIFEC CONGO")->from("sifec@mid.cg")
        ->attach($piece, [
            'as' => 'piece.pdf',
            'mime' => 'application/pdf'
            ]
            )

        ->view('mail.transmission_document_demande');
       // return $this->view('Mail.transmission_document');
    }
}
