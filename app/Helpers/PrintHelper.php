<?php

namespace App\Helpers;

use App\Models\Order;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Illuminate\Support\Facades\Log;

class PrintHelper
{
    /* ===============  KITCHEN ORDER PRINT  ================= */
    public static function printKitchenOrder(Order $order)
    {
        try {
            $connector = new WindowsPrintConnector("Kitchen_Printer");
            $printer = new Printer($connector);

            self::printHeader($printer, "KITCHEN ORDER");
            self::printOrderDetails($printer, $order);

            $printer->text("-----------------------------\n");
            $printer->text("Status: PENDING\n");
            $printer->feed(2);
            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            Log::error('Kitchen print failed: ' . $e->getMessage());
        }
    }

    /* ===============  COLD BAR ORDER PRINT  ================= */
    public static function printColdBarOrder(Order $order)
    {
        try {
            $connector = new WindowsPrintConnector("Cold_Bar_Printer");
            $printer = new Printer($connector);

            self::printHeader($printer, "COLD BAR ORDER");
            self::printOrderDetails($printer, $order);

            $printer->text("-----------------------------\n");
            $printer->text("Status: PENDING\n");
            $printer->feed(2);
            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            Log::error('Cold bar print failed: ' . $e->getMessage());
        }
    }

    /* ===============  KITCHEN DELETION SLIP  ================= */
    public static function printKitchenDeletionSlip($orderDetails, $reason)
    {
        try {
            $connector = new WindowsPrintConnector("Kitchen_Printer");
            $printer = new Printer($connector);

            self::printHeader($printer, "ORDER DELETION SLIP");
            self::printDeletedOrder($printer, $orderDetails, $reason);

            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            Log::error('Kitchen deletion print failed: ' . $e->getMessage());
        }
    }

    /* ===============  COLD BAR DELETION SLIP  ================= */
    public static function printColdBarDeletionSlip($orderDetails, $reason)
    {
        try {
            $connector = new WindowsPrintConnector("Cold_Bar_Printer");
            $printer = new Printer($connector);

            self::printHeader($printer, "ORDER DELETION SLIP");
            self::printDeletedOrder($printer, $orderDetails, $reason);

            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            Log::error('Cold bar deletion print failed: ' . $e->getMessage());
        }
    }

    /* ===============  COMMON: HEADER ================= */
    private static function printHeader(Printer $printer, $title)
    {
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setEmphasis(true);
        $printer->text("=== {$title} ===\n");
        $printer->setEmphasis(false);
        $printer->text(date('d M Y h:i A') . "\n");
        $printer->text("-----------------------------\n");
    }

    /* ===============  COMMON: ORDER DETAILS ================= */
    private static function printOrderDetails(Printer $printer, Order $order)
    {
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Order #: {$order->order_no}\n");
        $printer->text("Table #: {$order->table_no}\n");
        $printer->text("-----------------------------\n");

        $printer->setEmphasis(true);
        $printer->text("ITEM                QTY\n");
        $printer->setEmphasis(false);

        foreach ($order->items as $item) {
            $printer->text(str_pad($item['item'], 18));
            $printer->text(str_pad($item['qty'], 4) . "\n");
        }

        $printer->text("-----------------------------\n");
    }

    /* ===============  COMMON: DELETED ORDER DETAILS ================= */
    private static function printDeletedOrder(Printer $printer, $orderDetails, $reason)
    {
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Order #: {$orderDetails['order_no']}\n");
        $printer->text("Table #: {$orderDetails['table_no']}\n");
        $printer->text("-----------------------------\n");

        $printer->setEmphasis(true);
        $printer->text("ITEM                QTY\n");
        $printer->setEmphasis(false);

        if (!empty($orderDetails['items'])) {
            foreach ($orderDetails['items'] as $item) {
                $printer->text(str_pad($item['item'] ?? '—', 18));
                $printer->text(str_pad($item['qty'] ?? '-', 4) . "\n");
            }
        } else {
            $printer->text("No items found\n");
        }

        $printer->text("-----------------------------\n");
        $printer->setEmphasis(true);
        $printer->text("DELETION REASON:\n");
        $printer->setEmphasis(false);
        $printer->text($reason . "\n");
        $printer->feed(2);
    }
}
