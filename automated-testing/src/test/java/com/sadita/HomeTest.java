package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import java.util.List;
import static org.junit.jupiter.api.Assertions.*;

public class HomeTest extends BaseTest {

    @Test
    public void testTC02A_HomepageLoadsSuccessfully() {
        driver.get(baseUrl + "/");
        
        // Assert page title matches expected
        String title = driver.getTitle();
        assertTrue(title.contains("Sadita"), "Title should contain Sadita, got: " + title);
        
        // Assert critical sections are present using explicit waits
        WebElement berandaSection = waitForElementVisible(By.id("beranda"), 5);
        WebElement kategoriSection = waitForElementVisible(By.id("kategori"), 5);
        WebElement galeriSection = waitForElementVisible(By.id("galeri"), 5);
        WebElement lacakSection = waitForElementVisible(By.id("lacak"), 5);
        WebElement tentangSection = waitForElementVisible(By.id("tentang"), 5);
        
        assertNotNull(berandaSection, "Beranda section should be present");
        assertNotNull(kategoriSection, "Kategori section should be present");
        assertNotNull(galeriSection, "Galeri section should be present");
        assertNotNull(lacakSection, "Lacak section should be present");
        assertNotNull(tentangSection, "Tentang section should be present");
    }

    @Test
    public void testTC02B_CategoryQuickLinks() {
        driver.get(baseUrl + "/");
        
        // Verify quick link anchors are visible and clickable
        WebElement dekorasiLink = waitForElementClickable(By.xpath("//a[@href='#kategori-dekorasi']"), 5);
        assertNotNull(dekorasiLink, "Dekorasi link should exist");
        
        // Clicking quick links should modify the URL hash or target element scroll
        dekorasiLink.click();
        
        // Wait briefly for scroll action
        try { Thread.sleep(500); } catch (InterruptedException e) {}
        
        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.endsWith("#kategori-dekorasi") || currentUrl.contains("#kategori-dekorasi"), 
                "URL hash should scroll to #kategori-dekorasi, got: " + currentUrl);
    }

    @Test
    public void testTC02C_GalleryFilterTabs() {
        driver.get(baseUrl + "/");
        
        // Locate filter buttons with explicit wait
        WebElement filterAll = waitForElementClickable(By.xpath("//button[@data-filter='all']"), 5);
        WebElement filterDekorasi = waitForElementClickable(By.xpath("//button[@data-filter='dekorasi']"), 5);
        
        assertNotNull(filterAll, "Filter 'Semua' button should exist");
        assertNotNull(filterDekorasi, "Filter 'Dekorasi' button should exist");
        
        // Verify filtering works in DOM by clicking 'dekorasi' and checking hidden classes
        filterDekorasi.click();
        try { Thread.sleep(800); } catch (InterruptedException e) {} // Wait for masonry animation to complete
        
        // Verify active class on the button
        assertTrue(filterDekorasi.getAttribute("class").contains("active"), "Dekorasi filter button should be active");
        assertFalse(filterAll.getAttribute("class").contains("active"), "All filter button should no longer be active");
        
        // Verify filtered item visibility
        List<WebElement> hantaranItems = driver.findElements(By.xpath("//div[@data-category='hantaran']"));
        if (!hantaranItems.isEmpty()) {
            // Filtered items should be hidden (have masonry-item-hidden class or hidden attribute)
            String hantaranClass = hantaranItems.get(0).getAttribute("class");
            assertTrue(hantaranClass.contains("masonry-item-hidden") || hantaranItems.get(0).getAttribute("hidden") != null, 
                    "Hantaran items should be hidden when Dekorasi filter is active");
        }
    }
}
