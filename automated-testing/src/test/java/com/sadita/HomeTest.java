package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import java.util.List;
import static org.junit.jupiter.api.Assertions.*;

public class HomeTest extends BaseTest {

    @Test
    public void testTC05_HomepageLoadsSuccessfully() {
        driver.get(baseUrl + "/");
        
        // Assert page title matches expected
        String title = driver.getTitle();
        assertTrue(title.contains("Sadita"), "Title should contain Sadita, got: " + title);
        
        // Assert critical sections are present
        WebElement berandaSection = driver.findElement(By.id("beranda"));
        WebElement kategoriSection = driver.findElement(By.id("kategori"));
        WebElement galeriSection = driver.findElement(By.id("galeri"));
        WebElement lacakSection = driver.findElement(By.id("lacak"));
        WebElement tentangSection = driver.findElement(By.id("tentang"));
        
        assertNotNull(berandaSection, "Beranda section should be present");
        assertNotNull(kategoriSection, "Kategori section should be present");
        assertNotNull(galeriSection, "Galeri section should be present");
        assertNotNull(lacakSection, "Lacak section should be present");
        assertNotNull(tentangSection, "Tentang section should be present");
    }

    @Test
    public void testTC06_CategoryQuickLinks() {
        driver.get(baseUrl + "/");
        
        // Verify quick link anchors
        WebElement papanUcapanLink = driver.findElement(By.xpath("//a[@href='#kategori-papan-ucapan']"));
        WebElement hantaranLink = driver.findElement(By.xpath("//a[@href='#kategori-hantaran']"));
        WebElement dekorasiLink = driver.findElement(By.xpath("//a[@href='#kategori-dekorasi']"));
        
        assertNotNull(papanUcapanLink, "Papan Ucapan link should exist");
        assertNotNull(hantaranLink, "Hantaran link should exist");
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
    public void testTC07_GalleryFilterTabs() {
        driver.get(baseUrl + "/");
        
        // Locate filter buttons
        WebElement filterAll = driver.findElement(By.xpath("//button[@data-filter='all']"));
        WebElement filterDekorasi = driver.findElement(By.xpath("//button[@data-filter='dekorasi']"));
        WebElement filterHantaran = driver.findElement(By.xpath("//button[@data-filter='hantaran']"));
        WebElement filterPapan = driver.findElement(By.xpath("//button[@data-filter='papan']"));
        
        assertNotNull(filterAll, "Filter 'Semua' button should exist");
        assertNotNull(filterDekorasi, "Filter 'Dekorasi' button should exist");
        
        // Verify filtering works in DOM by clicking 'dekorasi' and checking hidden classes
        filterDekorasi.click();
        try { Thread.sleep(500); } catch (InterruptedException e) {}
        
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
