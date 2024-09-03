<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\FiscalNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FiscalNoteController extends Controller
{
    public function novaNotaFiscal(Request $request, $id)
    {
        // Valide o arquivo enviado
        $request->validate([
            'nota_fiscal' => 'required|mimes:pdf',
            'data_faturamento' => 'required|date',
            'data_lembrete' => 'required|date',
        ]);

        // Armazene o arquivo PDF
        $pdfFile = $request->file('nota_fiscal');
        $currentDate = Carbon::now()->format('YmdHis');
        $pdfFileName = "nota_fiscal_{$currentDate}.pdf";

        // Salve o PDF na pasta 'public/nf' (ou qualquer outra pasta que você deseje)
        $pdfPath = $pdfFile->storeAs('public/nf', $pdfFileName);

        // Crie uma nova instância de FiscalNote
        $fiscalNote = new FiscalNote([
            'filename' => $pdfFileName,
            'event_id' => $id,
            'payment_term' => $request->data_faturamento,
            'notification_date' => $request->data_lembrete,
            'observation' => $request->observacao_nota_fiscal,
        ]);

        $event = $fiscalNote->event;

        $event->note_emission_date = Carbon::now()->toDateString();
        // Salve a nota fiscal no banco de dados
        $fiscalNote->save();
        $event->save();

        return redirect()->back()->with('success', 'Nota fiscal adicionada com sucesso.');
    }

    public function excluirNotaFiscal($id)
    {
        $fiscalNote = FiscalNote::find($id);

        if (!$fiscalNote) {
            return redirect()->back()->with('error', 'Nota fiscal não encontrada.');
        }

        // Exclua o arquivo do servidor
        Storage::delete("public/nf/{$fiscalNote->filename}");

        // Exclua a entrada no banco de dados
        $fiscalNote->delete();
        $event = $fiscalNote->event;
        $event->note_emission_date = null;
        $event->receipt_date = null;
        $event->save();

        return redirect()->back()->with('success', 'Nota fiscal excluída com sucesso.');
    }

    public function mudarStatus($id)
    {
        $fiscalNote = FiscalNote::find($id);

        if (!$fiscalNote) {
            return redirect()->back()->with('error', 'Nota fiscal não encontrada.');
        }

        if ($fiscalNote->paid == 1) {
            $fiscalNote->paid = 0;
        } else {
            $fiscalNote->paid = 1;
        }

        $event = $fiscalNote->event;
        if ($fiscalNote->paid === 1) {
            $event->receipt_date = $fiscalNote->updated_at;
        } else {
            $event->receipt_date = null;
        }

        $fiscalNote->save();
        $event->save();

        return redirect()->back()->with('success', 'Nota fiscal marcada como paga com sucesso.');
    }
}
