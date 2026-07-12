package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import static org.junit.jupiter.api.Assertions.*;

public class OrderTest extends BaseTest {

    @Test
    public void testTC08_OrderPreFillFromQueryString() {
        // Go directly to order page with query params representing "Papan Standing Mirror Premium"
        String testProduct = "Papan Standing Mirror Premium";
        String testPrice = "Rp 150.000";
        String testImg = "/images/papan-standing-mirror-premium.jpg";
        String testJenis = "Papan Bunga";
        
        driver.get(baseUrl + "/order?product=" + testProduct + "&price=" + testPrice + "&img=" + testImg + "&jenis=" + testJenis);
        
        // Assert page header or hidden inputs have these values
        WebElement productNameInput = driver.findElement(By.name("product_name"));
        WebElement priceInput = driver.findElement(By.name("price"));
        WebElement jenisInput = driver.findElement(By.name("jenis"));
        
        assertEquals(testProduct, productNameInput.getAttribute("value"), "Product name should be prefilled");
        assertEquals(testPrice, priceInput.getAttribute("value"), "Price should be prefilled");
        assertEquals(testJenis, jenisInput.getAttribute("value"), "Product type should be prefilled");
        
        // Verify summary on the right shows the price
        WebElement totalPaymentText = driver.findElement(By.xpath("//span[contains(text(), 'Rp 150.000')]"));
        assertNotNull(totalPaymentText, "Right sidebar summary should show correct total payment");
    }

    @Test
    public void testTC09_PlaceOrderSuccess() {
        driver.get(baseUrl + "/order?product=Papan Bunga&price=Rp 100.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Fill order form
        driver.findElement(By.id("senderName")).sendKeys("Ahsan Ramadan");
        driver.findElement(By.id("senderPhone")).sendKeys("81234567890"); // note +62 prefix label on UI
        driver.findElement(By.id("receiverName")).sendKeys("Bagatio Putra Joandri");
        driver.findElement(By.id("untuk")).sendKeys("Grand Opening Cafe");
        driver.findElement(By.id("address")).sendKeys("Jalan Sudirman No. 12, Kota Padang");
        
        // Select Date (tomorrow to bypass past date constraint)
        WebElement datePicker = driver.findElement(By.id("deliveryDate"));
        java.time.LocalDate tomorrow = java.time.LocalDate.now().plusDays(1);
        datePicker.sendKeys(tomorrow.toString());
        
        // Waktu Pengiriman
        WebElement timeDropdown = driver.findElement(By.id("deliveryTime"));
        timeDropdown.sendKeys("Pagi (08:00 - 12:00)");
        
        // Greeting & Special Instruction
        driver.findElement(By.id("greetingMsg")).sendKeys("Selamat Sukses!");
        driver.findElement(By.id("specialInstruction")).sendKeys("Letakkan dekat pintu masuk");
        
        // Submit
        driver.findElement(By.cssSelector("button[type='submit']")).click();
        
        // Wait and assert we get redirected to the invoice/success page
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        
        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.contains("/invoice"), "Should redirect to invoice page, but got: " + currentUrl);
        
        // Check for order code
        WebElement orderCodeEl = driver.findElement(By.xpath("//div[contains(text(), 'SDT-') or contains(@class, 'order-code') or contains(text(), 'Kode Pesanan')]"));
        assertNotNull(orderCodeEl, "Invoice page should show the order code");
    }

    @Test
    public void testTC10_PlaceOrderMissingRequiredFields() {
        driver.get(baseUrl + "/order?product=Papan Bunga&price=Rp 100.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Fill some fields, leave name and phone empty
        driver.findElement(By.id("receiverName")).sendKeys("Jeli Mayora");
        driver.findElement(By.id("address")).sendKeys("Jalan Khatib Sulaiman");
        
        // Submit
        driver.findElement(By.cssSelector("button[type='submit']")).click();
        
        // HTML5 validation stops submission. Check that URL stays the same.
        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.contains("/order"), "Should remain on order page");
    }

    @Test
    public void testTC11_ApplyPromoCodeInvalid() {
        driver.get(baseUrl + "/order?product=Papan Bunga&price=Rp 100.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Fill order form
        driver.findElement(By.id("senderName")).sendKeys("Ahsan Ramadan");
        driver.findElement(By.id("senderPhone")).sendKeys("81234567890");
        driver.findElement(By.id("receiverName")).sendKeys("Bagatio");
        driver.findElement(By.id("address")).sendKeys("Padang");
        WebElement datePicker = driver.findElement(By.id("deliveryDate"));
        datePicker.sendKeys(java.time.LocalDate.now().plusDays(1).toString());
        driver.findElement(By.id("deliveryTime")).sendKeys("Pagi (08:00 - 12:00)");
        driver.findElement(By.cssSelector("button[type='submit']")).click();
        
        // Wait redirect to invoice
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        
        // On invoice page, try to apply a fake promo code
        WebElement promoInput = driver.findElement(By.name("promo_code"));
        promoInput.sendKeys("INVALIDCODE");
        
        WebElement applyBtn = driver.findElement(By.xpath("//button[contains(text(), 'Pakai')]"));
        applyBtn.click();
        
        // Wait for page reload
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        
        // Verify validation message
        WebElement errorEl = driver.findElement(By.xpath("//div[contains(text(), 'tidak ditemukan') or contains(text(), 'tidak aktif')]"));
        assertNotNull(errorEl, "Validation error message for invalid promo code should be shown");
    }

    @Test
    public void testTC12_ApplyPromoCodeValid() {
        driver.get(baseUrl + "/order?product=Papan Bunga&price=Rp 150.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Fill order form
        driver.findElement(By.id("senderName")).sendKeys("Ahsan Promo");
        driver.findElement(By.id("senderPhone")).sendKeys("81234567891");
        driver.findElement(By.id("receiverName")).sendKeys("Bagatio Promo");
        driver.findElement(By.id("address")).sendKeys("Padang Baru");
        WebElement datePicker = driver.findElement(By.id("deliveryDate"));
        datePicker.sendKeys(java.time.LocalDate.now().plusDays(2).toString());
        driver.findElement(By.id("deliveryTime")).sendKeys("Pagi (08:00 - 12:00)");
        driver.findElement(By.cssSelector("button[type='submit']")).click();
        
        // Wait redirect to invoice
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        
        // Apply valid promo code
        WebElement promoInput = driver.findElement(By.name("promo_code"));
        promoInput.sendKeys("SADITA10");
        
        WebElement applyBtn = driver.findElement(By.xpath("//button[contains(text(), 'Pakai')]"));
        applyBtn.click();
        
        // Wait for page reload
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        
        // Verify discount applies
        WebElement discountLabel = driver.findElement(By.xpath("//span[contains(text(), 'SADITA10') or contains(text(), 'Voucher')]"));
        assertNotNull(discountLabel, "Discount label for applied promo code should be visible");
    }

    @Test
    public void testTC13_PlaceOrderDokuRedirect() {
        driver.get(baseUrl + "/order?product=Papan Bunga&price=Rp 100.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Fill order form
        driver.findElement(By.id("senderName")).sendKeys("Doku User");
        driver.findElement(By.id("senderPhone")).sendKeys("81234567892");
        driver.findElement(By.id("receiverName")).sendKeys("Doku Receiver");
        driver.findElement(By.id("address")).sendKeys("Padang Doku");
        WebElement datePicker = driver.findElement(By.id("deliveryDate"));
        datePicker.sendKeys(java.time.LocalDate.now().plusDays(3).toString());
        driver.findElement(By.id("deliveryTime")).sendKeys("Pagi (08:00 - 12:00)");
        driver.findElement(By.cssSelector("button[type='submit']")).click();
        
        // Wait redirect to invoice
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        
        // Select payment method radio button if present, then click pay
        try {
            WebElement methodRadio = driver.findElement(By.cssSelector("input[type='radio'][name='payment_method']"));
            if (!methodRadio.isSelected()) {
                methodRadio.click();
            }
        } catch (Exception e) {}
        
        WebElement payBtn = driver.findElement(By.xpath("//button[contains(text(), 'Bayar sekarang')]"));
        payBtn.click();
        
        // Wait for redirect to DOKU sandbox page
        try { Thread.sleep(5000); } catch (InterruptedException e) {}
        
        // Assert redirect to DOKU sandbox happened
        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.contains("doku") || currentUrl.contains("sandbox") || currentUrl.contains("jokul"), 
                "Should redirect to DOKU sandbox payment gateway, got: " + currentUrl);
    }
}


