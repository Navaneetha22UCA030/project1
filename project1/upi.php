<div class="mb-3">
    <label class="form-label">Payment Method</label>
    <select name="paymentMethod" id="paymentMethod" class="form-control" required onchange="showUPIQRCode()">
        <option value="Cash on Delivery">Cash on Delivery</option>
        <option value="Online Payment">Online Payment</option>
    </select>
</div>

<!-- UPI QR Code Display -->
<div id="upiQRCode" class="text-center mt-3 d-none">
    <h5>Scan to Pay</h5>
    <img id="upiQR" src="" alt="UPI QR Code" class="img-fluid" style="max-width: 250px;">
    <p class="mt-2 text-muted">Scan with any UPI app (Google Pay, PhonePe, Paytm)</p>
</div>
