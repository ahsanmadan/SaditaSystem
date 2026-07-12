package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import static org.junit.jupiter.api.Assertions.*;

public class OrderTest extends BaseTest {

    @Test
    public void testTC05A_OrderPrefill() {
        // Accessing the order page with query parameters
        driver.get(baseUrl + "/order?product=Papan Bunga Premium&price=Rp 200.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Use explicit wait to check if values are prefilled in the read-only card
        WebElement productTitle = waitForElementVisible(By.xpath("//*[contains(text(), 'Papan Bunga Premium')]"), 5);
        WebElement productPrice = waitForElementVisible(By.xpath("//*[contains(text(), 'Rp 200.000')]"), 5);
        
        assertNotNull(productTitle, "Product title should be prefilled from URL");
        assertNotNull(productPrice, "Product price should be prefilled from URL");
    }

    @Test
    public void testTC05B_PlaceOrderSuccess() {
        driver.get(baseUrl + "/order?product=Papan Bunga Premium&price=Rp 200.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Fill order form using explicit wait to wait for fields to load
        WebElement senderName = waitForElementVisible(By.id("senderName"), 5);
        WebElement senderPhone = waitForElementVisible(By.id("senderPhone"), 5);
        WebElement receiverName = waitForElementVisible(By.id("receiverName"), 5);
        WebElement address = waitForElementVisible(By.id("address"), 5);
        WebElement datePicker = waitForElementVisible(By.id("deliveryDate"), 5);
        WebElement deliveryTime = waitForElementVisible(By.id("deliveryTime"), 5);
        WebElement submitBtn = waitForElementClickable(By.cssSelector("button[type='submit']"), 5);
        
        senderName.sendKeys("Automated Ahsan");
        senderPhone.sendKeys("81234567890");
        receiverName.sendKeys("Receiver Jeli");
        address.sendKeys("Jalan Limau Manis, Padang");
        
        // Select a future date (tomorrow)
        java.time.LocalDate tomorrow = java.time.LocalDate.now().plusDays(1);
        datePicker.sendKeys(tomorrow.toString());
        
        deliveryTime.sendKeys("Pagi (08:00 - 12:00)");
        
        submitBtn.click();
        
        // Verify redirection to invoice page (URL should contain /invoice/)
        assertTrue(waitForUrlContains("/invoice/", 5), "Should redirect to invoice page");
        
        // Verify invoice details load correctly
        WebElement statusLabel = waitForElementVisible(By.xpath("//span[contains(text(), 'Menunggu Pembayaran') or contains(text(), 'UNPAID')]"), 5);
        assertNotNull(statusLabel, "Invoice status 'Menunggu Pembayaran' should be shown");
    }

    @Test
    public void testTC05C_PlaceOrderMissingRequired() {
        driver.get(baseUrl + "/order?product=Papan Bunga Premium&price=Rp 200.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        WebElement senderName = waitForElementVisible(By.id("senderName"), 5);
        WebElement submitBtn = waitForElementClickable(By.cssSelector("button[type='submit']"), 5);
        
        senderName.clear(); // Leave sender name empty
        submitBtn.click();
        
        // HTML5 required validation should stop submission. URL stays on /order
        assertTrue(driver.getCurrentUrl().contains("/order"), "Should stay on order page due to validation failure");
    }

    @Test
    public void testTC28B_ApplyPromoCodeInvalid() {
        driver.get(baseUrl + "/order?product=Papan Bunga&price=Rp 150.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Fill order form
        waitForElementVisible(By.id("senderName"), 5).sendKeys("Promo Check");
        waitForElementVisible(By.id("senderPhone"), 5).sendKeys("81234567899");
        waitForElementVisible(By.id("receiverName"), 5).sendKeys("Receiver Promo");
        waitForElementVisible(By.id("address"), 5).sendKeys("Padang Baru");
        waitForElementVisible(By.id("deliveryDate"), 5).sendKeys(java.time.LocalDate.now().plusDays(2).toString());
        waitForElementVisible(By.id("deliveryTime"), 5).sendKeys("Pagi (08:00 - 12:00)");
        waitForElementClickable(By.cssSelector("button[type='submit']"), 5).click();
        
        // Wait redirect to invoice
        assertTrue(waitForUrlContains("/invoice/", 5), "Should redirect to invoice page");
        
        // On invoice page, try to apply a fake promo code
        WebElement promoInput = waitForElementVisible(By.name("promo_code"), 5);
        promoInput.sendKeys("INVALIDCODE");
        
        WebElement applyBtn = waitForElementClickable(By.xpath("//button[contains(text(), 'Pakai')]"), 5);
        applyBtn.click();
        
        // Verify validation message
        WebElement errorEl = waitForElementVisible(By.xpath("//div[contains(text(), 'tidak ditemukan') or contains(text(), 'tidak aktif')]"), 5);
        assertNotNull(errorEl, "Validation error message for invalid promo code should be shown");
    }

    @Test
    public void testTC28A_ApplyPromoCodeValid() {
        driver.get(baseUrl + "/order?product=Papan Bunga&price=Rp 150.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Fill order form
        waitForElementVisible(By.id("senderName"), 5).sendKeys("Ahsan Promo");
        waitForElementVisible(By.id("senderPhone"), 5).sendKeys("81234567891");
        waitForElementVisible(By.id("receiverName"), 5).sendKeys("Bagatio Promo");
        waitForElementVisible(By.id("address"), 5).sendKeys("Padang Baru");
        waitForElementVisible(By.id("deliveryDate"), 5).sendKeys(java.time.LocalDate.now().plusDays(2).toString());
        waitForElementVisible(By.id("deliveryTime"), 5).sendKeys("Pagi (08:00 - 12:00)");
        waitForElementClickable(By.cssSelector("button[type='submit']"), 5).click();
        
        // Wait redirect to invoice
        assertTrue(waitForUrlContains("/invoice/", 5), "Should redirect to invoice page");
        
        // Apply valid promo code
        WebElement promoInput = waitForElementVisible(By.name("promo_code"), 5);
        promoInput.sendKeys("SADITA10");
        
        WebElement applyBtn = waitForElementClickable(By.xpath("//button[contains(text(), 'Pakai')]"), 5);
        applyBtn.click();
        
        // Verify discount applies
        WebElement discountLabel = waitForElementVisible(By.xpath("//span[contains(text(), 'SADITA10') or contains(text(), 'Voucher')]"), 5);
        assertNotNull(discountLabel, "Discount label for applied promo code should be visible");
    }

    @Test
    public void testTC31A_PlaceOrderDokuRedirect() {
        driver.get(baseUrl + "/order?product=Papan Bunga&price=Rp 100.000&img=/images/hero-1.jpg&jenis=Papan Bunga");
        
        // Fill order form
        waitForElementVisible(By.id("senderName"), 5).sendKeys("Doku User");
        waitForElementVisible(By.id("senderPhone"), 5).sendKeys("81234567892");
        waitForElementVisible(By.id("receiverName"), 5).sendKeys("Doku Receiver");
        waitForElementVisible(By.id("address"), 5).sendKeys("Padang Doku");
        waitForElementVisible(By.id("deliveryDate"), 5).sendKeys(java.time.LocalDate.now().plusDays(3).toString());
        waitForElementVisible(By.id("deliveryTime"), 5).sendKeys("Pagi (08:00 - 12:00)");
        waitForElementClickable(By.cssSelector("button[type='submit']"), 5).click();
        
        // Wait redirect to invoice
        assertTrue(waitForUrlContains("/invoice/", 5), "Should redirect to invoice page");
        
        // Select payment method radio button if present, then click pay
        try {
            WebElement methodRadio = waitForElementClickable(By.cssSelector("input[type='radio'][name='payment_method']"), 5);
            if (!methodRadio.isSelected()) {
                methodRadio.click();
            }
        } catch (Exception e) {}
        
        WebElement payBtn = waitForElementClickable(By.xpath("//button[contains(text(), 'Bayar sekarang')]"), 5);
        payBtn.click();
        
        // Wait for redirect to DOKU sandbox page and stop testing there
        assertTrue(waitForUrlContains("doku", 8) || waitForUrlContains("sandbox", 8) || waitForUrlContains("jokul", 8), 
                "Should redirect to DOKU sandbox page");
    }
}
