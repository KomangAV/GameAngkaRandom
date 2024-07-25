<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('number')) {
            $this->reset($request);
        }

        return view('game', [
            'lives' => $request->session()->get('lives', 5),
            'hints' => $request->session()->get('hints', []),
            'hint_count' => $request->session()->get('hint_count', 2),
            'range_hint' => $request->session()->get('range_hint', '')
        ]);
    }

    public function guess(Request $request)
    {
        $request->validate([
            'guess' => 'required|integer|min:1|max:100',
        ]);

        $guess = $request->input('guess');
        $number = $request->session()->get('number');
        $lives = $request->session()->get('lives');

        if ($guess == $number) {
            $message = __('messages.congratulations');
            $request->session()->forget('number');
        } else {
            $message = $guess < $number ? __('messages.too_low') : __('messages.too_high');
            $lives -= 0.5; // Kurangi setengah nyawa
            $request->session()->put('lives', $lives);

            if ($lives <= 0) {
                $message = __('messages.game_over');
                $request->session()->forget('number');
            }
        }

        return redirect('/')->with('message', $message);
    }

    public function hint(Request $request)
    {
        $lives = $request->session()->get('lives');
        $hintCount = $request->session()->get('hint_count', 2);
        $hints = $request->session()->get('hints', []);
        
        if ($lives <= 0.5 || $hintCount <= 0) {
            return redirect('/')->with('message', __('messages.not_enough_lives'));
        }

        $number = $request->session()->get('number');
        
        if (count($hints) == 0) {
            $hint = ($number % 2 == 0) ? __('messages.hint_even') : __('messages.hint_odd');
            $hints[] = $hint;
            $request->session()->put('hints', $hints);
            $request->session()->put('lives', $lives - 0.5); // Kurangi setengah nyawa
            $request->session()->put('hint_count', $hintCount - 1); // Kurangi jumlah hint yang tersisa
            $message = __('messages.hint_used');
        } elseif (count($hints) == 1) {
            $rangeStart = max(1, $number - 40);
            $rangeEnd = min(100, $number + 40);
            $hint = __('messages.hint_range', ['start' => $rangeStart, 'end' => $rangeEnd]);
            $request->session()->put('range_hint', $hint);
            $request->session()->put('lives', $lives - 0.5); // Kurangi setengah nyawa
            $request->session()->put('hint_count', $hintCount - 1); // Kurangi jumlah hint yang tersisa
            $message = __('messages.hint_used');
        } else {
            $message = __('messages.all_hints_used');
        }

        return redirect('/')->with('message', $message);
    }

    public function reset(Request $request)
    {
        $number = rand(1, 100);
        $request->session()->put('number', $number);
        $request->session()->put('lives', 5);
        $request->session()->put('hints', []);
        $request->session()->put('hint_count', 2); // Reset jumlah hint
        $request->session()->put('range_hint', '');

        return redirect('/')->with('message', __('messages.game_reset'));
    }
}
