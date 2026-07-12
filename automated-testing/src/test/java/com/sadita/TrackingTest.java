package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import static org.junit.jupiter.api.Assertions.*;

public class TrackingTest extends BaseTest {

    @Test
    public void testTC17A_TrackOrderSuccess() {
        // Create an order first, get its code from the invoice page, and then track it!
        driver.get(baseUrl + "/order?product=Hantaran&price=Rp 50.000&img=/images/hero-1.jpg&jenis=Hantaran");
        
        waitForElementVisible(By.id("senderName"), 5).sendKeys("Tracking Buyer");
        waitForElementVisible(By.id("senderPhone"), 5).sendKeys("89653090248");
        waitForElementVisible(By.id("receiverName"), 5).sendKeys("Tracking Receiver");
        waitForElementVisible(By.id("address"), 5).sendKeys("Jalan A Yani Padang");
        
        WebElement datePicker = waitForElementVisible(By.id("deliveryDate"), 5);
        java.time.LocalDate tomorrow = java.time.LocalDate.now().plusDays(2);
        datePicker.sendKeys(tomorrow.toString());
        waitForElementVisible(By.id("deliveryTime"), 5).sendKeys("Siang (12:00 - 16:00)");
        
        waitForElementClickable(By.cssSelector("button[type='submit']"), 5).click();
        
        // Wait redirect to invoice
        assertTrue(waitForUrlContains("/invoice/", 5), "Should redirect to invoice page");
        
        // We are on invoice page, extract the order ID from URL or page text
        String url = driver.getCurrentUrl();
        String orderId = url.substring(url.lastIndexOf("/") + 1);
        
        // Go back to Homepage and track
        driver.get(baseUrl + "/");
        
        // Re-locate elements with explicit waits
        WebElement input = waitForElementVisible(By.id("trackingInput"), 5);
        WebElement button = waitForElementClickable(By.id("trackingBtn"), 5);
        
        input.clear();
        input.sendKeys(orderId);
        button.click();
        
        // Result should be visible and show matching ID and UNPAID status
        WebElement resultBox = waitForElementVisible(By.id("trackingResult"), 5);
        assertTrue(resultBox.isDisplayed(), "Tracking result should be visible");
        assertTrue(resultBox.getText().contains("UNPAID") || resultBox.getText().contains("Menunggu Pembayaran"), 
                "Should show unpaid status for new order");
    }

    @Test
    public void testTC17B_TrackOrderNotFound() {
        driver.get(baseUrl + "/");
        
        WebElement input = waitForElementVisible(By.id("trackingInput"), 5);
        WebElement button = waitForElementClickable(By.id("trackingBtn"), 5);
        
        input.clear();
        input.sendKeys("SDT-NOTFOUND-999");
        button.click();
        
        WebElement resultBox = waitForElementVisible(By.id("trackingResult"), 5);
        assertTrue(resultBox.isDisplayed(), "Tracking result should be visible");
        assertTrue(resultBox.getText().contains("Tidak Ditemukan") || resultBox.getText().contains("gagal") || resultBox.getText().contains("found"), 
                "Should show 'Tidak Ditemukan' message");
    }
}
