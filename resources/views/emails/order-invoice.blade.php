<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>RYO Invoice {{ $invoice->invoice_number }}</title>
    <style>
        /* Email-safe responsive enhancements */
        @media only screen and (max-width: 600px) {
            .responsive-header td {
                display: block !important;
                width: 100% !important;
                text-align: left !important;
                padding: 8px 0 !important;
            }

            .responsive-header td:first-child {
                padding-bottom: 16px !important;
            }

            .invoice-card {
                border-radius: 24px !important;
            }

            .product-table-wrapper {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }

            .product-table {
                min-width: 500px !important;
            }

            .totals-mobile {
                width: 100% !important;
                text-align: right !important;
            }

            .cta-button {
                width: 80% !important;
                font-size: 15px !important;
                padding: 14px 20px !important;
            }

            .social-links span {
                display: inline-block !important;
                margin: 6px 8px !important;
            }

            .mobile-padding {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .invoice-title {
                font-size: 28px !important;
            }

            .brand-badge {
                font-size: 10px !important;
            }
        }

        /* General reset */
        .ExternalClass,
        .ReadMsgBody {
            width: 100%;
            background-color: #f8f6f2;
        }

        body,
        table,
        td,
        p,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f5f2ee;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
        }
    </style>
</head>

<body
    style="margin:0;padding:0;background:#f5f2ee;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;">

    @php
        $order = $invoice->onlineOrder;
        $details = $invoice->salesInvoiceDetails;
        $shippingCost = (float) ($order?->shipping_cost ?? 0);
        $subtotal = (float) $invoice->total_amount;
        $discount = (float) $invoice->deduction;
        $grandTotal = (float) $invoice->net_total;
        $orderNumber = $order?->order_number ?? ($order?->id ?? null);
        $customerName = $order?->customer_name ?? ($invoice->customer?->customer_name ?? 'Valued Customer');
        $customerPhone = $order?->customer_phone ?? ($invoice->customer?->contact_info ?? '');
        $customerEmail = $order?->customer_email ?? '';
        $shippingAddress = $order?->address ? trim($order->address . ($order->area ? ', ' . $order->area : '')) : null;
        $invoiceDate =
            $invoice->created_at?->format('F j, Y, g:i a') ??
            ($invoice->created_at?->format('Y-m-d H:i') ?? date('Y-m-d H:i'));
        $invoiceNumber = $invoice->invoice_number;
        // Website URL (adjust to your actual store URL)
        $websiteUrl = 'https://ryoegy.com'; // or use config('app.url') if available
        $socialLinks = [
            'instagram' => 'https://www.instagram.com/ryobrandofficial',
            'facebook' => 'https://www.facebook.com/RYObrand.official',
            'tiktok' => 'https://www.tiktok.com/@ryobrand.official',
        ];
    @endphp

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f2ee;padding:36px 16px;">
        <tr>
            <td align="center" style="padding:0;">
                <!-- Main Invoice Card -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="invoice-card"
                    style="max-width:680px;margin:0 auto;background:#ffffff;border-radius:32px;box-shadow:0 20px 35px -12px rgba(0,0,0,0.08),0 2px 6px rgba(0,0,0,0.02);overflow:hidden;">

                    <!-- Header: Brand + Invoice ID -->
                    <tr>
                        <td style="padding:36px 40px 24px 40px;border-bottom:1px solid #ede6df;background:#ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="vertical-align:middle;">
                                        <div
                                            style="font-size:11px;letter-spacing:2.8px;text-transform:uppercase;color:#b48b5a;font-weight:600;margin-bottom:10px;">
                                            RYO • Crafted Confidence</div>
                                        <h1
                                            style="margin:0;font-size:36px;font-weight:500;letter-spacing:-0.5px;color:#1e1b18;line-height:1.1;">
                                            Invoice</h1>
                                        <div style="margin-top:12px;">
                                            <span
                                                style="background:#f2efe8;padding:5px 14px;border-radius:60px;font-size:13px;color:#4f453a;font-weight:500;">{{ $invoiceNumber }}</span>
                                        </div>
                                    </td>
                                    <td align="right" style="vertical-align:bottom;">
                                        <div style="text-align:right;">
                                            <div
                                                style="font-size:12px;color:#8e8273;margin-bottom:8px;letter-spacing:0.3px;">
                                                Issue date</div>
                                            <div style="font-size:17px;font-weight:500;color:#2c2620;">
                                                {{ $invoiceDate }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <p
                                style="margin:22px 0 0 0;color:#6a5f54;font-size:14px;line-height:1.45;border-left:3px solid #dbbd9a;padding-left:18px;">
                                Thank you for your order. This document serves as your official invoice.
                            </p>
                        </td>
                    </tr>

                    <!-- Customer & Order Details (refined card) -->
                    <tr>
                        <td style="padding:0 40px 28px 40px;background:#ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="background:#fefcf9;border-radius:24px;border:1px solid #eee7e0;">
                                <tr>
                                    <td style="padding:24px 28px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                            class="responsive-header">
                                            <tr>
                                                <td style="vertical-align:top;width:50%;padding-right:20px;">
                                                    <div
                                                        style="font-size:11px;letter-spacing:1.6px;text-transform:uppercase;color:#b48b5a;margin-bottom:14px;font-weight:700;">
                                                        BILL TO</div>
                                                    <p
                                                        style="margin:0 0 8px;font-size:16px;font-weight:600;color:#1e1b18;">
                                                        {{ $customerName }}</p>
                                                    @if ($customerPhone)
                                                        <p style="margin:0 0 6px;font-size:14px;color:#6a5f54;">📞
                                                            {{ $customerPhone }}</p>
                                                    @endif
                                                    @if ($customerEmail)
                                                        <p style="margin:0;font-size:14px;color:#6a5f54;">✉️
                                                            {{ $customerEmail }}</p>
                                                    @endif
                                                </td>
                                                <td style="vertical-align:top;width:50%;padding-left:20px;">
                                                    <div
                                                        style="font-size:11px;letter-spacing:1.6px;text-transform:uppercase;color:#b48b5a;margin-bottom:14px;font-weight:700;">
                                                        ORDER DETAILS</div>

                                                    <p style="margin:0 0 6px;font-size:14px;color:#2c2620;"><span
                                                            style="color:#8e8273;">Invoice date:</span>
                                                        {{ $invoice->created_at?->format('d M Y') ?? '' }}</p>
                                                    @if ($shippingAddress)
                                                        <p
                                                            style="margin:10px 0 0 0;font-size:14px;color:#6a5f54;line-height:1.45;">
                                                            📍 {{ $shippingAddress }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Order Items with Product Images -->
                    <tr>
                        <td style="padding:0 40px 28px 40px;background:#ffffff;">
                            <div style="margin-bottom:14px;">
                                <span style="font-size:16px;font-weight:600;color:#1e1b18;letter-spacing:-0.2px;">Order
                                    summary</span>
                            </div>
                            <div class="product-table-wrapper"
                                style="border-radius:24px;overflow:hidden;border:1px solid #eee7e0;background:#ffffff;">
                                <table class="product-table" role="presentation" width="100%" cellspacing="0"
                                    cellpadding="0" style="width:100%;border-collapse:collapse;">
                                    <thead>
                                        <tr style="background:#faf8f5;">
                                            <th align="left"
                                                style="padding:16px 12px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:#7c6e60;">
                                                Item</th>
                                            <th align="center"
                                                style="padding:16px 8px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:#7c6e60;">
                                                Qty</th>
                                            <th align="right"
                                                style="padding:16px 12px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:#7c6e60;">
                                                Price</th>
                                            <th align="right"
                                                style="padding:16px 12px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:#7c6e60;">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($details as $detail)
                                            @php
                                                $variant = $detail->productVariant;
                                                $product = $variant?->product ?? null;
                                                $productName = $product?->product_name ?? 'RYO Signature Product';
                                                $variantLabel = collect([
                                                    $variant?->color?->color_name,
                                                    $variant?->size?->size_name,
                                                ])
                                                    ->filter()
                                                    ->implode(' / ');
                                                $quantity = (int) $detail->product_quantity;
                                                $unitPrice = (float) $detail->unit_price;
                                                $lineTotal = $unitPrice * $quantity;

                                                // Extract product image URL from stored public product images
                                                $productImageUrl = null;
                                                if ($product?->primaryImage?->image_path) {
                                                    $productImageUrl = \Illuminate\Support\Facades\Storage::url($product->primaryImage->image_path);
                                                }
                                                // If still empty, use a subtle placeholder (inline SVG/data-uri)
                                                if (!$productImageUrl) {
                                                    $productImageUrl =
                                                        'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="%23f0ece6"%3E%3Crect width="40" height="40" fill="%23f4f0ea"/%3E%3Cpath d="M14 15h12v10H14z" fill="%23d6ccbf"/%3E%3C/svg%3E';
                                                }
                                            @endphp
                                            <tr style="border-top:1px solid #f0eae3;">
                                                <td style="padding:16px 12px;border:none;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0"
                                                        style="width:100%;">
                                                        <tr>
                                                            <td
                                                                style="width:52px;vertical-align:middle;padding-right:14px;">
                                                                <img src="{{ $productImageUrl }}"
                                                                    alt="{{ $productName }}" width="48"
                                                                    height="48"
                                                                    style="display:block;border-radius:12px;object-fit:cover;border:1px solid #eee5dc;" />
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                <div
                                                                    style="font-size:15px;font-weight:500;color:#2c2620;">
                                                                    {{ $productName }}</div>
                                                                @if ($variantLabel)
                                                                    <div
                                                                        style="margin-top:4px;font-size:12px;color:#a18e7a;">
                                                                        {{ $variantLabel }}</div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td align="center"
                                                    style="padding:16px 8px;font-size:15px;color:#2c2620;border:none;font-weight:500;">
                                                    {{ $quantity }}</td>
                                                <td align="right"
                                                    style="padding:16px 12px;font-size:15px;color:#4a3f35;border:none;">
                                                    EGP {{ number_format($unitPrice, 2) }}</td>
                                                <td align="right"
                                                    style="padding:16px 12px;font-size:15px;font-weight:600;color:#1e1b18;border:none;">
                                                    EGP {{ number_format($lineTotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <!-- Totals + CTA Button & Social Links -->
                    <tr>
                        <td style="padding:0 40px 32px 40px;background:#ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="right" style="padding:0 0 20px 0;">
                                        <table role="presentation" width="280" cellspacing="0" cellpadding="0"
                                            align="right" style="width:100%;max-width:320px;">
                                            <tr style="border-bottom:1px solid #efe7df;">
                                                <td style="padding:10px 0;color:#6a5f54;font-size:14px;">Subtotal</td>
                                                <td align="right"
                                                    style="padding:10px 0;color:#2c2620;font-size:14px;">EGP
                                                    {{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 0;color:#6a5f54;font-size:14px;">Shipping</td>
                                                <td align="right"
                                                    style="padding:10px 0;color:#2c2620;font-size:14px;">
                                                    @if ($shippingCost > 0)
                                                        EGP {{ number_format($shippingCost, 2) }}
                                                    @else
                                                        <span style="color:#9b8a78;">Free</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if ($discount > 0)
                                                <tr>
                                                    <td style="padding:8px 0;color:#b66d4b;font-size:14px;">Discount
                                                    </td>
                                                    <td align="right"
                                                        style="padding:8px 0;color:#b66d4b;font-size:14px;">- EGP
                                                        {{ number_format($discount, 2) }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td
                                                    style="padding:16px 0 8px 0;border-top:2px solid #e3d9d0;font-size:18px;font-weight:700;color:#1e1b18;">
                                                    Total Due</td>
                                                <td align="right"
                                                    style="padding:16px 0 8px 0;border-top:2px solid #e3d9d0;font-size:21px;font-weight:800;color:#b48b5a;letter-spacing:-0.3px;">
                                                    EGP {{ number_format($grandTotal, 2) }}</td>
                                            </tr>
                                        </table>
                                </tr>
                    </tr>
                </table>

                <!-- Enhanced Button: Visit Our Store -->
                <div style="text-align:center;margin:24px 0 20px;">
                    <a href="{{ $websiteUrl }}"
                        style="display:inline-block;background:#1e1b18;color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;padding:14px 32px;border-radius:60px;letter-spacing:0.5px;box-shadow:0 4px 8px rgba(0,0,0,0.05);transition:0.2s;border:1px solid #33302d;">✨
                        Shop RYO Collection →</a>
                </div>

                <!-- Social Media Links with elegant inline display -->
                <div style="text-align:center;margin-top:18px;border-top:1px solid #efe7df;padding-top:24px;">
                    <p style="margin:0 0 12px 0;font-size:12px;color:#8e8273;letter-spacing:0.6px;">FOLLOW OUR JOURNEY
                    </p>
                    <div style="text-align:center;padding-top:10px;">
                        <div
                            style="

        justify-content:center;
        align-items:center;
        gap:14px;
        flex-wrap:wrap;
    ">

                            @if (isset($socialLinks['instagram']))
                                <a href="{{ $socialLinks['instagram'] }}" target="_blank"
                                    style="
                    display:inline-flex;
                    align-items:center;
                    gap:8px;
                    padding:10px 16px;
                    border:1px solid #e7d9ca;
                    border-radius:999px;
                    text-decoration:none;
                    color:#111;
                    background:#faf7f3;
                    font-size:13px;
                    font-weight:600;
                    transition:all .2s ease;
               ">

                                    <img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" width="16"
                                        height="16" alt="Instagram" style="display:block;">

                                    <span>Instagram</span>
                                </a>
                            @endif

                            @if (isset($socialLinks['facebook']))
                                <a href="{{ $socialLinks['facebook'] }}" target="_blank"
                                    style="
                    display:inline-flex;
                    align-items:center;
                    gap:8px;
                    padding:10px 16px;
                    border:1px solid #e7d9ca;
                    border-radius:999px;
                    text-decoration:none;
                    color:#111;
                    background:#faf7f3;
                    font-size:13px;
                    font-weight:600;
               ">

                                    <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" width="16"
                                        height="16" alt="Facebook" style="display:block;">

                                    <span>Facebook</span>
                                </a>
                            @endif

                            @if (isset($socialLinks['tiktok']))
                                <a href="{{ $socialLinks['tiktok'] }}" target="_blank"
                                    style="
                    display:inline-flex;
                    align-items:center;
                    gap:8px;
                    padding:10px 16px;
                    border:1px solid #e7d9ca;
                    border-radius:999px;
                    text-decoration:none;
                    color:#111;
                    background:#faf7f3;
                    font-size:13px;
                    font-weight:600;
               ">

                                    <img src="https://cdn-icons-png.flaticon.com/512/3046/3046121.png" width="16"
                                        height="16" alt="TikTok" style="display:block;">

                                    <span>TikTok</span>
                                </a>
                            @endif

                        </div>
                    </div>
                </div>


            </td>
        </tr>

        <!-- Footer with contact & legal -->
        <tr>
            <td style="background:#1e1b18;padding:28px 40px 32px 40px;text-align:center;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td
                            style="color:#d6cdc2;font-size:12px;letter-spacing:1.2px;text-transform:uppercase;font-weight:500;padding-bottom:16px;">
                            RYO — everyday confidence</td>
                    </tr>
                    <tr>
                        <td style="color:#b3a18e;font-size:13px;line-height:1.6;">
                            <span style="display:inline-block;margin:0 10px;">✉️ info.ryo.brand@gmail.com</span>
                            <span style="display:inline-block;margin:0 10px;">📞 +20 155 805 6772</span>
                            <span style="display:inline-block;margin:0 10px;">🌐 ryoegy.com</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:20px;color:#7c6b59;font-size:11px;">
                            This is an electronically generated invoice — no signature required.<br>
                            RYO Brand | Timeless quality, modern comfort.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- subtle footer note -->
    <div style="text-align:center;margin-top:24px;font-size:11px;color:#a09284;">
        🌿 RYO · Crafted with intention, built to last
    </div>
    </td>
    </tr>
    </table>
</body>

</html>
