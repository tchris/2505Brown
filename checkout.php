<form action="storeinvoice.php" method="post">
  <h2>Billing Info</h2>
  <input name="cname" placeholder="Name" required>
  <input name="caddy" placeholder="Address" required>
  <input name="cstate" placeholder="State" required>
  <input name="czip" placeholder="Zip" required>
  <input name="cphone" placeholder="Phone" required>
  <input name="cemail" placeholder="Email" required>

  <h2>Shipping Info</h2>
  <input name="sname" placeholder="Name" required>
  <input name="saddy" placeholder="Address" required>
  <input name="sstate" placeholder="State" required>
  <input name="szip" placeholder="Zip" required>
  <input name="sphone" placeholder="Phone" required>
  <input name="semail" placeholder="Email" required>

  <button type="submit">Submit Order</button>
</form>