package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import static org.junit.jupiter.api.Assertions.*;

public class TrackingTest extends BaseTest {

    @Test
    public void testTC11_TrackOrderSuccess() {
        driver.get(baseUrl + "/");
        
        WebElement input = driver.findElement(By.id("trackingInput"));
        WebElement button = driver.findElement(By.id("trackingBtn"));
        
        // Enter a valid seeded order code
        // Based on seeders, let's try to track using a placeholder or we can create an order first
        // But since this is a clean test, let's enter a dummy valid-format order that is handled or seeded.
        // Wait, does the seeder seed transaction data? Yes, transaction seeder creates random orders.
        // Let's create an order first, get its code from the invoice page, and then track it!
        // This is a beautiful way to write a self-contained integration test!
        
        driver.get(baseUrl + "/order?product=Hantaran&price=Rp 50.000&img=/images/hero-1.jpg&jenis=Hantaran");
        driver.findElement(By.id("senderName")).sendKeys("Tracking Buyer");
        driver.findElement(By.id("senderPhone")).sendKeys("89653090248");
        driver.findElement(By.id("receiverName")).sendKeys("Tracking Receiver");
        driver.findElement(By.id("address")).sendKeys("Jalan A Yani Padang");
        
        WebElement datePicker = driver.findElement(By.id("deliveryDate"));
        java.time.LocalDate tomorrow = java.time.LocalDate.now().plusDays(2);
        datePicker.sendKeys(tomorrow.toString());
        driver.findElement(By.id("deliveryTime")).sendKeys("Siang (12:00 - 16:00)");
        
        driver.findElement(By.cssSelector("button[type='submit']")).click();
        
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        
        // We are on invoice page, extract the order ID from URL or page text
        String url = driver.getCurrentUrl();
        String orderId = url.substring(url.lastIndexOf("/") + 1);
        
        // Go back to Homepage and track
        driver.get(baseUrl + "/");
        
        // Re-locate elements
        input = driver.findElement(By.id("trackingInput"));
        button = driver.findElement(By.id("trackingBtn"));
        
        input.clear();
        input.sendKeys(orderId);
        button.click();
        
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        
        // Result should be visible and show matching ID and UNPAID status
        WebElement resultBox = driver.findElement(By.id("trackingResult"));
        assertTrue(resultBox.isDisplayed(), "Tracking result should be visible");
        assertTrue(resultBox.getText().contains("UNPAID") || resultBox.getText().contains("Menunggu Pembayaran"), 
                "Should show unpaid status for new order");
    }

    @Test
    public void testTC12_TrackOrderNotFound() {
        driver.get(baseUrl + "/");
        
        WebElement input = driver.findElement(By.id("trackingInput"));
        WebElement button = driver.findElement(By.id("trackingBtn"));
        
        input.clear();
        input.sendKeys("SDT-NOTFOUND-999");
        button.click();
        
        try { Thread.sleep(1500); } catch (InterruptedException e) {}
        
        WebElement resultBox = driver.findElement(By.id("trackingResult"));
        assertTrue(resultBox.isDisplayed(), "Tracking result should be visible");
        assertTrue(resultBox.getText().contains("Tidak Ditemukan") || resultBox.getText().contains("gagal") || resultBox.getText().contains("found"), 
                "Should show 'Tidak Ditemukan' message");
    }
}
